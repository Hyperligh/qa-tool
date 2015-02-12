using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using MySql.Data.MySqlClient;

namespace QA_2
{
    class Data_Handler
    {

        //Variable Definitions
       MySqlConnection ConnectToPullData;
       public DateTime CheckIn = DateTime.Now;


        //Main Loop That Screens For Data Related Requests
        public Data_Handler()
        {
        }

        public void Starter()
        {
            ConnectToPullData = Form1.ConnectToPullData;
            //This is where all data will be handled. 
            /* For pulling data, a function will be written in Data_Pull that gets the required information
             * That information will be pushed to a public list and handled in a seperate thread accordingly
             * Information that needs to be pushed to the database will be submitted to 
            */
            var Loop_Forever = true;

            while (Loop_Forever == true)
            {
                //Data pull handles pulling data and putting it in the appropriate lists
                Data_Pull();
                //Check for cancelled actually removes items for the To_Process, Processed, and All_URL lists.
                //This prevents having to pass to a new list that the domain was cancelled, that is then read by the data moniter, that is then passed back to the data handler
                Check_For_Cancelled();
                //Data push looks through a list of subbmitted queries from all over the application and just submits them.
                Data_Push();
                //Check in so watchman know we are in business
                CheckIn = DateTime.Now;

                System.Threading.Thread.Sleep(2000);




            }
        }


        private void Data_Pull()
        {


          //Temporary List that is used to hang on to queues that are found so that they can be deleted from the 'pass_domain' table after being gathered
            List<KeyValuePair<String, String>> Query_Results = new List<KeyValuePair<String, String>>();

            //Pull Queued Domains
            MySqlCommand Command = ConnectToPullData.CreateCommand();
                Command.CommandText = "select Domain, code, multidomain, mobile FROM pass_domain order by time;";
                try
                {
                    ConnectToPullData.Open();
                }
                catch (Exception)
                {
                }

                MySqlDataReader Reader = Command.ExecuteReader();

                while (Reader.Read())
                {
                    //% arrays containg domains, codes, multidomains
                    //A: Yeah, this is that actual data pulled from the database

                    String Domain = Reader["Domain"].ToString();  
                    String Code = Reader["code"].ToString();
                    String MultiDomain = Reader["multidomain"].ToString();
                    String Mobile = Reader["mobile"].ToString();

                    //Pass 'To_Process' List info on domain, url, domain_code, url_code, multi-domain, source_url (domain_code = url_code in this case becuase this is the domain being passed)
                    Form1.To_Process.Add(new String[8] { Domain, Domain, Code, Code, MultiDomain, "", "", Mobile});  //% why no explicitg source URL, load time
                   
                    //A: This is the domain that is being passed, so at this point there is no source url or load time
                    System.Threading.Thread.Sleep(10);

                    //Report Results To Public List
                    var Queued_Domain_KV = new KeyValuePair<String, String>(Domain, Code);
                    Form1.Queued_Domain.Add(new KeyValuePair<KeyValuePair<String,String>,String>(Queued_Domain_KV, "Running"));
                    //Pass 'To_Process' List info on domain, url, domain_code, url_code, multi-domain, source_url (domain_code = url_code in this case becuase this is the domain being passed)
                    Form1.All_URLs.Add(new KeyValuePair<string,string>(Code, Domain));

                    //Add To Temporary List
                    Query_Results.Add(new KeyValuePair<String, String>(Domain, Code));

                }
                Reader.Close();
                Reader.Dispose();
                ConnectToPullData.Close();
                ConnectToPullData.Dispose();
            // End Pull

            //Delete from pass_domain so they don't get re-ran
            //% can't for the life of me find where pass_domains is defined
                /* //A:
                 * It's not a variable within the c# code. It is the table in the database that the domain is passed through. 
                 * ^^ Up there where is says "select Domain, code, multidomain FROM pass_domain..." this an is actual sql query that is looking any domains posted through the webfront end
                 * what this second half down here does is; IF any domains were found through that query it will put that information in the list "Query_Results"... 
                 * =>if anything is in "Query_Results" that means something was found in that pass_domain table, in which case it needs to be delete so it isn't ran more than once
                 * That is what this line of code down here does .. 
                 * "DELETE FROM pass_domain WHERE code ='" + Domain.Value + "';
                 * This is the sql command to delete the result we just found
                 * */
                foreach (var Domain in Query_Results)
                {
                    //Delete The Returned Query Results from the pass domain table so the don't run again
                    MySqlCommand Delete_From_PassDomain = new MySqlCommand("DELETE FROM pass_domain WHERE code ='" + Domain.Value + "';", ConnectToPullData);
                    try
                    {
                        ConnectToPullData.Open();
                    }
                    catch (Exception)
                    {
                    }
                    Delete_From_PassDomain.ExecuteNonQuery();
                    //Update the queue log to indicate that the queue has been submitted and is running
                    MySqlCommand Update_Status = new MySqlCommand("UPDATE que_log SET status = 'running' WHERE Domain = '" + Domain.Key + "' and Code = '" + Domain.Value + "';", ConnectToPullData);
                    try
                    {
                        ConnectToPullData.Open();
                    }
                    catch (Exception)
                    {
                    }
                    Update_Status.ExecuteNonQuery();





                    ConnectToPullData.Close();
                    ConnectToPullData.Dispose();
                    GC.WaitForFullGCComplete();
                 }




                Query_Results.Clear();
            //End Data Pull
                   
             }

        private void Check_For_Cancelled()
        {

                //Pull Queued Domains Status's
            try
            {
                foreach (var Single_Queued_Domain in Form1.Queued_Domain)
                {
                    MySqlCommand Command = ConnectToPullData.CreateCommand();
                    Command.CommandText = "select status FROM que_log where code = '" + Single_Queued_Domain.Key.Value + "' and status = 'Cancelled';";
                    try
                    {
                        ConnectToPullData.Open();
                    }
                    catch (Exception)
                    {
                    }

                    MySqlDataReader Reader = Command.ExecuteReader();

                    while (Reader.Read())
                    {
                        String Domain_Code = Single_Queued_Domain.Key.Value;
                        Form1.To_Process.RemoveAll(x => x.ElementAt(2) == Domain_Code);
                        Form1.Processed.RemoveAll(x => x.ElementAt(2) == Domain_Code);
                        Form1.All_URLs.RemoveAll(x => x.Key == Domain_Code);
                    }
                    Reader.Close();
                    Reader.Dispose();
                }


                ConnectToPullData.Close();
                ConnectToPullData.Dispose();
            }
            catch (Exception)
            {
            }

        }

        private void Data_Push()
        {
            Push_All_Data();
        }

        private void Push_All_Data()
        {

            //If there is data to push in the queue. Loop through queue until count is zero
            if (Form1.DataPush.Count > 0)
            {
                while (Form1.DataPush.Count > 0)
                {


                        //Hold first element in list
                        String cmd_hold = Form1.DataPush.FirstOrDefault();
                        //Set command
                        MySqlCommand cmd = new MySqlCommand(cmd_hold, ConnectToPullData);
                        //Try to open connection
                        try
                        {
                            ConnectToPullData.Open(); //error found Invalid Operation 
                            cmd.ExecuteNonQuery(); //error in sql syntax
                        }
                        catch
                        {
                        }
  
                        //Remove From List
                        Form1.DataPush.Remove(cmd_hold);
                        ConnectToPullData.Close();
                        ConnectToPullData.Dispose();




                }
            }

        }
       






    }
}
