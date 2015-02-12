using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace QA_2
{
    class Duplicate_Title
    {

        public Duplicate_Title(String Domain, String URL, String SourceUrl, String Domain_Code, String URL_Code, String Title)
        {
            String ErrorInTitle = "";

            Title = Title.ToLower().Replace(",", "");
            List<String> TitleWords = Title.Split(' ').ToList();
            int TitleErrorCount = 0;

            //Check to see if title ends in and
            if (TitleWords.LastOrDefault() == "and")
            {
                ErrorInTitle = " Title ends in and ";
                //If Error Found. Submit to errors table
                String ValueString = "('" + Domain + "', '" + URL + "', '" + SourceUrl + "', '" + Domain_Code + "', '" + URL_Code + "', 'TitleError', '" + ErrorInTitle + "')";
                String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                Form1.DataPush.Add(Query);
            }
            //Count the number of duplicate words in the title
            while (TitleWords.Count > 1)
            {
                //Get the first word in the list
                String TitleWord = TitleWords.FirstOrDefault();
                //Get initial number of words in list
                int StartNum = TitleWords.Count;
                //Remove all instances of that word
                int NumDuplicates = TitleWords.RemoveAll(x => x == TitleWord);
                //Count the difference in the size of the list, subtract one to ajust for it counting itself
                int Duplicates = (StartNum - TitleWords.Count) - 1;
                //Add Duplicates to titile total if word is longer than one character
                if (TitleWord.Length > 1)
                {
                    TitleErrorCount = TitleErrorCount + Duplicates;
                }
            }


            if (TitleErrorCount > 4)
            {
                ErrorInTitle = TitleErrorCount.ToString() + " duplicate words in title";
                String ValueString = "('" + Domain + "', '" + URL + "', '" + SourceUrl + "', '" + Domain_Code + "', '" + URL_Code + "', 'TitleError', '" + ErrorInTitle + "')";
                String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                Form1.DataPush.Add(Query);
            }

        }


    }
}
