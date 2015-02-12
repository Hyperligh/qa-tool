using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using OpenQA.Selenium;
using OpenQA.Selenium.PhantomJS;
using System.Diagnostics;

namespace QA_2
{
    class monitor_domains_new
    {

        public DateTime CheckIn = DateTime.Now;



        public void Starter(){
            while (true)
            {
                try
                {
                    Form1.Queued_Domain = Form1.Queued_Domain.Distinct().ToList();
                    Form1.To_Process = Form1.To_Process.Distinct().ToList();
                }
                catch (Exception)
                {
                }

                Find_Finished();
                Update_Load_Status();
                Queue_Browsers();
                //Check in so watchman know we are in business
                CheckIn = DateTime.Now;
                System.Threading.Thread.Sleep(1000);
            }
        }

        private void Find_Finished()
        {

            //If there are domains in the queued domain list. Check to see if they are done
            if (Form1.Queued_Domain.Count > 0)
            {
                //Reminder  ** Key = Domain_String - Value = Domain_Code in  ** in  -Queued_Domain-

                //Checking the to_process and processing lists to see if anything is still being ran for that specfic domain entry.
                //To_Process.element(0) == The Domain String and To_Process.element(2) = The Domain String.

                //Temp Set Done is a the list the will hold the data temporarily that is to be removed then set done 
                var Temp_Add_Set_Done = new List<KeyValuePair<KeyValuePair<String, String>, String>>();
                foreach (var Single_Queued_Domain in Form1.Queued_Domain)
                {
                    String Queued_Domain_Sting = Single_Queued_Domain.Key.Key;
                    String Queued_Domain_Code = Single_Queued_Domain.Key.Value;
                    try
                    {
                        int Num_To_Process = Form1.To_Process.FindAll(x => x.ElementAt(0) == Queued_Domain_Sting && x.ElementAt(2) == Queued_Domain_Code).Count();
                        int Num_Processing = Form1.Processing.FindAll(x => x.Value.ElementAt(0) == Queued_Domain_Sting && x.Value.ElementAt(2) == Queued_Domain_Code).Count();
                        int Num_Processed = Form1.Processed.FindAll(x => x.ElementAt(0) == Queued_Domain_Sting && x.ElementAt(2) == Queued_Domain_Code).Count();



                        //If there is nothing in to_process list for this domain, and nothing processing, and things have been processesed. The domain is done
                        if (Num_To_Process < 1 && Num_Processed > 0 && Num_Processing < 1)
                        {
                            //Update que_log to pergartory.
                            //This was added because the front end needs to update the status to done.
                            //To avoid the ajax having to constantly update the already fisihed domains, but still keep the functionality of havin the domains finish on ajax
                            //An intermediate step was added. The status s now set to done by the front end
                            
                            Temp_Add_Set_Done.Add(Single_Queued_Domain);
                            String cmd_hold = "UPDATE que_log SET status = 'purgatory', URLs_Ran = '" + Num_Processed + "' WHERE Domain = '" + Single_Queued_Domain.Key.Key + "' and Code = '" + Single_Queued_Domain.Key.Value + "' and status != 'Cancelled';";
                            Form1.DataPush.Add(cmd_hold);



                        }
                    }
                    catch (Exception)
                    {
                    }

                }

                foreach (var To_Delete_KVPair in Temp_Add_Set_Done)
                {
                    //Why are things not being cleared form lists here? 
                    //Adding clearing atesting effects
                    Form1.Queued_Domain.Remove(To_Delete_KVPair);
                    //Form1.Queued_Domain.Add(new KeyValuePair<KeyValuePair<String, String>, String>(To_Delete_KVPair.Key, "Done"));
                    Form1.Processed.RemoveAll(x => x.ElementAt(2) == To_Delete_KVPair.Value);
                    Form1.All_URLs.RemoveAll(x => x.Key == To_Delete_KVPair.Key.Value);
                }

                Temp_Add_Set_Done.Clear();

            }

        }

        private void Update_Load_Status()
        {
            //If there are domains in the queued domain list. Check to see if they are done
            if (Form1.Queued_Domain.Count > 0)
            {
                //Reminder  ** Key = Domain_String - Value = Domain_Code in  ** in  -Queued_Domain-

                //Checking the to_process and processing lists to see if anything is still being ran for that specfic domain entry.
                //To_Process.element(0) == The Domain String and To_Process.element(2) = The Domain String.
                foreach (var Single_Queued_Domain in Form1.Queued_Domain) // error found InvalidOperationException
                {
                    String Queued_Domain_Sting = Single_Queued_Domain.Key.Key;
                    String Queued_Domain_Code = Single_Queued_Domain.Key.Value;

                    int Num_To_Process = Form1.To_Process.FindAll(x => x.ElementAt(0) == Queued_Domain_Sting && x.ElementAt(2) == Queued_Domain_Code).Count();
                    int Num_Processing = Form1.Processing.FindAll(x => x.Value.ElementAt(0) == Queued_Domain_Sting && x.Value.ElementAt(2) == Queued_Domain_Code).Count();  //error found - Null reference exception
                    int Num_Processed = Form1.Processed.FindAll(x => x.ElementAt(0) == Queued_Domain_Sting && x.ElementAt(2) == Queued_Domain_Code).Count();

                    String cmd_hold = "UPDATE que_log SET URLs_Ran = '" + Num_Processed + "', URLs_Remaining = '" + Num_To_Process + "' WHERE Domain = '" + Single_Queued_Domain.Key.Key + "' and code = '" + Single_Queued_Domain.Key.Value + "' and status != 'Cancelled';";
                    Form1.DataPush.Add(cmd_hold);


                }

            }
        }
        
        // Random class for getting random domain code
        static Random rnd = new Random();

        private void Queue_Browsers()
        {

            List<IWebDriver> ToRemove = new List<IWebDriver>();
            //ERROR THROWN HERE. "ERROR COLLECTION MODIFIED"
            
            foreach (IWebDriver SingleBrowser in Form1.OpenBrowsers) //invalid operation exception was unhandled ERROR FOUND
                                                                     // 1500 URLS deep on www.capitasnowboarding.com/ domain, 
            {
                try
                {
                    if (Form1.To_Process.Count > 0)
                    {
               
                        
                        //creates a distinct list of domain codes
                        IEnumerable<String> Unique_Domains = Form1.To_Process.Select(x => x.ElementAt(2)).Distinct();

                        // This is probably because I be dumb, but I can't index on an ienumerable list :(
                        List<string> Unique_Domains_List = new List<string>();
                        foreach (string item in Unique_Domains)
                        {
                            Unique_Domains_List.Add(item);
                        }

                        //grabs a random number with a max size of the length of Unique_Domains list
                        int random_int = rnd.Next(Unique_Domains.Count());
                        string random_code = Unique_Domains_List[random_int];

                        var First_URL = Form1.To_Process.Find(x => x.ElementAt(2) == random_code);
                        
                        //Trace.Listeners.Add(new TextWriterTraceListener(Console.Out));
                        //Trace.WriteLine(The_URL.ElementAt(0));
                         
                        

                        //var First_URL = Form1.To_Process.FirstOrDefault();
                        if (First_URL.ElementAt(0).Length > 0 && First_URL.ElementAt(1).Length > 0)
                        {
                            String Domain = First_URL.ElementAt(0);
                            String URL = First_URL.ElementAt(1);
                            String Domain_Code = First_URL.ElementAt(2);
                            String URL_Code = First_URL.ElementAt(3);
                            String MultiDomain = First_URL.ElementAt(4);
                            String SourceURL = First_URL.ElementAt(5);
                            String Anchor = First_URL.ElementAt(6);
                            String Mobile = First_URL.ElementAt(7);
                            //The url that is loaded and the load time will be variables 7 and 8 and will  be set after url is loaded
                            String[] URL_Package = new String[10] { Domain, URL, Domain_Code, URL_Code, MultiDomain, SourceURL, "", "", Anchor, Mobile};

                            //Add url to the processing list, remove it from to_process 
                            Form1.Processing.Add(new KeyValuePair<IWebDriver, String[]>(SingleBrowser, URL_Package));

                            //Add it to the browser queue
                            Form1.Browser_Queue.Add(new KeyValuePair<IWebDriver, String[]>(SingleBrowser, URL_Package));
                            //Add to remove list to take out of Open browsers list later
                            ToRemove.Add(SingleBrowser);
                        }
                        Form1.To_Process.Remove(First_URL);

                    }
                }
                catch (Exception)
                {
                }
            }

            //Remove used browsers from open list
            foreach (IWebDriver DriverToRemove in ToRemove)
            {
                while (Form1.OpenBrowsers.FindAll(x => x == DriverToRemove).Count > 0)
                {
                    Form1.OpenBrowsers.RemoveAll(x => x == DriverToRemove);
                }
            }
            ToRemove.Clear();

        }





    }
}
