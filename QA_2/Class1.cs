using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace QA_2
{
    class Class1
    {
        public void CheckDuplicateTitle()
        {

            int TitleErrorCount = 0;
            List<String> TempList = new List<string>();
            TempString = GetDataBrowser.Title;
            TempList = TempString.Split(' ').ToList<string>();
            TempString = "";

            if (TempList.LastOrDefault().ToLower() == "and")
            {
                TextErrorString = TextErrorString + " - title ends in and  - ";
            }


            do
            {
                TempString = TempList.FirstOrDefault();
                TempList.Remove(TempString);
                foreach (string word in TempList)
                {
                    if (word == TempString)
                    {
                        TitleErrorCount = TitleErrorCount + 1;
                    }
                }
            }
            while (TempList.Count > 1);

            if (TitleErrorCount > 0)
            {
                TitleTextDuplicate = TitleErrorCount.ToString();
            }

        }
    }
}
