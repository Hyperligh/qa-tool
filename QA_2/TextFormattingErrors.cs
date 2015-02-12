using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace QA_2
{
    class TextFormattingErrors
    {
        public String ReturnFormattingErrors = "";
        public TextFormattingErrors(String Domain, String URL, String SourceUrl, String Domain_Code, String URL_Code, String AllText, String Title)
        {

            //Create list of HTML entities 
            List<String> Errors = new List<string>();
            Errors.Add("&#");
            Errors.Add("&quot");
            Errors.Add("&apos");
            Errors.Add("&amp");
            Errors.Add("&lt");
            Errors.Add("&gt");
            Errors.Add("&cent");
            Errors.Add("&copy");
            Errors.Add("&sup");
            Errors.Add("&para");
            Errors.Add("&middot");
            Errors.Add("&atilde");
            Errors.Add("&aacute");

            Errors.Add("\\'");
            Errors.Add("\\" + "\"");
            Errors.Add("'\\");
            Errors.Add("s's");
            Errors.Add("{");
            Errors.Add("}");

            Errors.Add("link to");


            foreach (String HtmlEntity in Errors)
            {
                if (AllText.Contains(HtmlEntity))
                {
                    String Error = HtmlEntity;
                    if (HtmlEntity.Contains("'"))
                    {
                        Error = "Backslash or apostrophe error found";
                    }

                    String ValueString = "('" + Domain + "', '" + URL + "', '" + SourceUrl + "', '" + Domain_Code + "', '" + URL_Code + "', 'TextError', '" + Error + "')";
                    String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                    Form1.DataPush.Add(Query);
                }
                if (Title.Contains(HtmlEntity))
                {
                    String Error = HtmlEntity;

                    if (HtmlEntity.Contains("\\"))
                    {
                        Error = "Backslash or apostrophe error found";
                    }

                    String ValueString = "('" + Domain + "', '" + URL + "', '" + SourceUrl + "', '" + Domain_Code + "', '" + URL_Code + "', 'TextError', '" + Error + "')";
                    String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                    Form1.DataPush.Add(Query);
                }
            }


        }



    }
}
