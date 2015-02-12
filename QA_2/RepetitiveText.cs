using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace QA_2
{
    class RepetitiveText
    {
        public RepetitiveText(String Domain, String URL, String SourceUrl, String Domain_Code, String URL_Code, String AllText)
        {

            //Take all text from page. Split on new line
            var BreakText = AllText.Split(new string[] { Environment.NewLine }, StringSplitOptions.None);
            //Reformat into list
            List<String> ListSentance = BreakText.ToList();

            //Words list will be used to check for repetitive text on a individual word level
            List<String> Words = new List<string>();
            //InteriorSentences will house the different sentences within a div
            List<String> InteriorSentances = new List<string>();
            //Reference string will be the variable compared to while looping through text
            String ReferenceString = "referencestringrandomstring";


            foreach (var Sentance in ListSentance)
            {
                //Split the divs text up into different sentences
                InteriorSentances = Sentance.Split('.').ToList();
                //For each sentence check for repetitive text
                foreach (var SingleSentance in InteriorSentances)
                {
                    //Set reference string
                    ReferenceString = "referencestringrandomstring";
                    //Get rid of the commas and split the words into a list
                    Words = SingleSentance.Split(' ').ToList();
                    //For each word, if the word before it is the same, the text is repetitive
                    foreach (String Word in Words)
                    {
                        
                                


                                //If match found, submit error
                        if (Word == ReferenceString & Word != "so")
                        {
                            if (Word.Length > 0)
                            {

                                Boolean ContainsLetters = false;
                                foreach (Char letter in Word)
                                {
                                    if (Char.IsLetter(letter))
                                    {
                                        ContainsLetters = true;
                                    }
                                }
                                if (ContainsLetters == true)
                                {
                                    String ValueString = "('" + Domain + "', '" + URL + "', '" + SourceUrl + "', '" + Domain_Code + "', '" + URL_Code + "', 'RepetitiveText', 'Word " + Word.Replace("'", "") + " is duplicated')";
                                    String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                                    Form1.DataPush.Add(Query);
                                }
                            }
                            //Set reference string to the current word
                        }
                        ReferenceString = Word;
                    }


                }

            }

        }
             


            
            
        

        /*

                        }
                else if (InnerText.ToLower() + "s" == RepetitiveTextString.ToLower())
                {
                    RepetitiveTextString = InnerText;
                    ListRepetitiveText.Add(InnerText);
                }
                else if (InnerText.ToLower() == RepetitiveTextString.ToLower() + "s")
                {
                    RepetitiveTextString = InnerText;
                    ListRepetitiveText.Add(InnerText);
                }
                else if (InnerText.ToLower() + "'s" == RepetitiveTextString.ToLower())
                {
                    RepetitiveTextString = InnerText;
                    ListRepetitiveText.Add(InnerText);
                }
                else if (InnerText.ToLower() == RepetitiveTextString.ToLower() + "'s")
                {
                    RepetitiveTextString = InnerText;
                    ListRepetitiveText.Add(InnerText);
                }


        */
    }
}
