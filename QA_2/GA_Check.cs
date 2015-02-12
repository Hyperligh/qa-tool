using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using OpenQA.Selenium;
using OpenQA.Selenium.Firefox;
using System.Diagnostics;

namespace QA_2
{
    class GA_Check
    {
        public GA_Check(String Domain_String, String URL_String, String Source_ID, String Domain_Code, String URL_Code, List<IWebElement> Scripts)
        {

           //Ignore jpegs and pngs and /floorplan/ pdf pages
            if (URL_String.Contains("/coupons/") == false && URL_String.Contains(".jpg") == false && URL_String.Contains(".png") == false && URL_String.Contains("/floorplans/") == false)
            {
                Boolean done = false;
                Boolean Found = false;
                List<string> anytext = new List<string>();
                while (done == false)
                {
                    try
                    {
                        foreach (var script in Scripts)
                        {
                            if (script.GetAttribute("innerHTML").Contains("UA-"))
                            {
                                Found = true;
                                done = true;
                            }
                        }

                    }
                    catch (Exception)
                    {
                        done = true;
                    }
                    if (Found == false)
                    {
                        //If the loop hasn't been exited yet, the means no GA script was found and it should be reported
                        String ValueString = "('" + Domain_String + "', '" + URL_String + "', '" + Source_ID + "', '" + Domain_Code + "', '" + URL_Code + "', 'GA_E','No GA script found')";
                        String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                        Form1.DataPush.Add(Query);
                        done = true;
                    }

                }
            }
        }
    }
}
