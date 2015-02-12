using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace QA_2
{
    class Broken_Pages
    {

        public Boolean Code_Found = false;
        public Boolean Response_Found = false;
        public Broken_Pages(String Domain, String URL, String SourceUrl, String Domain_Code, String URL_Code, String Title, String AllText)
        {


            var All_Done = false;
            while (All_Done == false)
            {
                Title = Title.ToLower().Replace(",", "");
                AllText = AllText.ToLower().Replace(",", "");
                List<String> BrokenPageIndicators = new List<string>();
                BrokenPageIndicators.Add("page not found");
                BrokenPageIndicators.Add("looking for doesn't exist");
                BrokenPageIndicators.Add("sorry but something went wrong");
                BrokenPageIndicators.Add("opendns");
                BrokenPageIndicators.Add("open dns");
                BrokenPageIndicators.Add("http");


                //Look through text for known server errors (Specified in form1) if found, submit to errors
                foreach (var Error_Code in Form1.ErrorResponseCodes)
                {

                    if (AllText.Contains(Error_Code.Key))
                    {
                        Code_Found = true;
                    }
                    else if (Title.Contains(Error_Code.Key))
                    {
                        Code_Found = true;
                    }

                    if (AllText.Contains(Error_Code.Value))
                    {
                        Response_Found = true;
                    }
                    else if (Title.Contains(Error_Code.Value))
                    {
                        Response_Found = true;
                    }


                    if (Code_Found == true && Response_Found == true)
                    {
                        String ValueString = "('" + Domain + "', '" + URL + "', '" + SourceUrl + "', '" + Domain_Code + "', '" + URL_Code + "', 'LoadingError', '" + Error_Code.Key + " " + Error_Code.Value + "')";
                        String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                        Form1.DataPush.Add(Query);
                        All_Done = true;
                        break;
                    }


                }




                //Check Title and Header first to end loop and save processing power if error is found
                foreach (String Broken_Check in BrokenPageIndicators)
                {
                    if (Title.Contains(Broken_Check) && All_Done == false)
                    {

                        String ValueString = "('" + Domain + "', '" + URL + "', '" + SourceUrl + "', '" + Domain_Code + "', '" + URL_Code + "', 'LoadingError', '" + Broken_Check + "')";
                        String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                        Form1.DataPush.Add(Query);
                        All_Done = true;
                    }
                }

                BrokenPageIndicators.Remove("http");
                //Check All Text for errors
                foreach (String Broken_Check in BrokenPageIndicators)
                {
                    if (AllText.Contains(Broken_Check) && All_Done == false)
                    {
                        String ValueString = "('" + Domain + "', '" + URL + "', '" + SourceUrl + "', '" + Domain_Code + "', '" + URL_Code + "', 'LoadingError', '" + Broken_Check + "')";
                        String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                        Form1.DataPush.Add(Query);
                        All_Done = true;
                    }
                }
                //At the end, set alldone = true to end the loop
                All_Done = true;
            }
        }
    }
}
