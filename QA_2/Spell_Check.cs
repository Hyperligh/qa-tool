using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using NHunspell;

namespace QA_2
{
    class Spell_Check
    {
        public Spell_Check(String Domain_String, String URL_String, String Source_ID, String Domain_Code, String URL_Code, List<String> AllWords)
        {

             using (Hunspell hunspell = new Hunspell("en_us.aff", "en_us.dic"))
            {

                String SpellingErrors = "";
                List<String> SpellList = new List<string>();
                Boolean ProcessWord = false;
                foreach (String TextString in AllWords)
                {
                    //Set process word to true. This way if at any time the word is detected as being a 'not word', we can stop the loop
                    ProcessWord = true;
                    //Loop while process word = true
                    while (ProcessWord == true)
                    {
                        //
                        if (Form1.SpellingMods.Contains(TextString))
                        {
                            ProcessWord = false;
                        }

                        //Check each word to make sure it is a contains all lower case letters
                        // **TO DOM** If you wanted to start checking uppercase words, this is where I would start
                        foreach (char letter in TextString)
                        {
                            if (Char.IsLetter(letter) == false)
                            {
                                ProcessWord = false;
                            }
                            else if (Char.IsUpper(letter) == true)
                            {
                                ProcessWord = false;
                            }
                        }

                        //Even though it's in a loop relying on process word being true, I'm checking to make sure its valid again I guess
                        if (ProcessWord == true)
                        {
                            //This checks hunspell to see if it is a word
                            bool correct = hunspell.Spell(TextString);
                            if (correct != true)
                            {
                                //If the word not true
                                SpellList.Add(TextString);
                            }
                        }
                        ProcessWord = false;
                    }
                    //End process word
                }
                //End loop words 

                //format found speliing errors
                Boolean isfirst = true;
                SpellList = SpellList.Distinct().ToList();
                //all the isfirst business is just to make the string look pretty
                foreach (String word in SpellList)
                {

                    if (Form1.SpellingMods.Contains(word) == false)
                    {
                        /*
                        if (isfirst == true)
                        {
                            SpellingErrors = word;
                            isfirst = false;
                        }
                        else
                        {
                            //SpellingErrors = SpellingErrors + ",  " + word + "";
                        }
                        */
                        SpellingErrors = word;
                        SpellingErrors = SpellingErrors.Replace("'", "");
                        String ValueString = "('" + Domain_String + "', '" + URL_String + "', '" + Source_ID + "', '" + Domain_Code + "', '" + URL_Code + "', 'SpellingError', '" + SpellingErrors + "')";
                        String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                        Form1.DataPush.Add(Query);
                    }


                }
                
                 if(SpellingErrors.Length > 0)
                 {
                   //  SpellingErrors = SpellingErrors.Replace("'", "");
                   //   String ValueString = "('" + Domain_String + "', '" + URL_String + "', '" + Source_ID + "', '" + Domain_Code + "', '" + URL_Code + "', 'SpellingError', '" + SpellingErrors + "')";
                   //   String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                   //   Form1.DataPush.Add(Query);
                 }
                SpellList.Clear();
                

            }

        }


        }
    }

