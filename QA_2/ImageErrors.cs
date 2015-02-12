using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using OpenQA.Selenium;

namespace QA_2
{
    class ImageErrors
    {
        public ImageErrors(String Domain_String, String URL_String, String Source_ID, String Domain_Code, String URL_Code, String AllTextOnPage, List<IWebElement> Images)
        {
            if (AllTextOnPage.Contains("image not found"))
            {
                String ValueString = "('" + Domain_String + "', '" + URL_String + "', '" + Source_ID + "', '" + Domain_Code + "', '" + URL_Code + "', 'ImageError', 'image not found')";
                String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                Form1.DataPush.Add(Query);
            }

            foreach (var Image in Images)
            {
                String AltText = Image.GetAttribute("alt").ToLower();
                String Source = Image.GetAttribute("src");
                if (AltText == "undefined")
                {
                    /*
                    String ValueString = "('" + Domain_String + "', '" + URL_String + "', '" + Source_ID + "', '" + Domain_Code + "', '" + URL_Code + "', 'ImageError', 'alt text undefined at " + Source.Replace("'","") + "')";
                    String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                    Form1.DataPush.Add(Query);
                     */
                }
                else if (Source.Contains("googleapis") == false && AltText == "")
                {
                    String ValueString = "('" + Domain_String + "', '" + URL_String + "', '" + Source_ID + "', '" + Domain_Code + "', '" + URL_Code + "', 'ImageError', 'Blank alt text at " + Source.Replace("'", "") + "')";
                    String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                    Form1.DataPush.Add(Query);
                }


            }




        }
    }
}
