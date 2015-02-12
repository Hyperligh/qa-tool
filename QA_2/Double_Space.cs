using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace QA_2
{
    class Double_Space
    {
        public Double_Space(String Domain, String URL, String SourceUrl, String Domain_Code, String URL_Code, String AllText)
        {
            try
            {
                //Set Splitter String
                string[] splitter = new string[] { "  " };
                //Seperate on that splitter string
                var Double_Spaces = AllText.Split(splitter, StringSplitOptions.None).ToList();

                bool found = false;

                int i = 0;
                foreach (String split in Double_Spaces)
                {
                    if (Char.IsLetter(split.FirstOrDefault()) && i > 0 && Char.IsLetter(Double_Spaces[i - 1].Last()))
                    {
                        found = true;
                    }
                    i++;
                }


                if (found == true)
                {
                    String Error = "Double Space Found";
                    String ValueString = "('" + Domain + "', '" + URL + "', '" + SourceUrl + "', '" + Domain_Code + "', '" + URL_Code + "', 'TextError', '" + Error + "')";
                    String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                    Form1.DataPush.Add(Query);
                }
            }
            catch (Exception) { }

            }
        }

    
}
