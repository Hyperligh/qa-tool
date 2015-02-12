using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using OpenQA.Selenium;
using OpenQA.Selenium.Firefox;
using System.ComponentModel;
using NUnit.Framework;
using System.Text.RegularExpressions;
using System.Diagnostics;

namespace QA_2
{
    class process_new
    {

        //Driver Lists
        List<IWebDriver> CrawlerWebBrowsers = new List<IWebDriver>();
        List<IWebDriver> OpenBrowsers = new List<IWebDriver>();
        public static DateTime CheckIn = DateTime.Now;

        public process_new(List<IWebDriver> PassWebBrowsers)
        {
            CrawlerWebBrowsers = PassWebBrowsers;
            Main_Loop();
        }

        public void Starter(List<IWebDriver> PassWebBrowsers)
        {
            CrawlerWebBrowsers = PassWebBrowsers;
            Main_Loop();
        }





        //--------- Main Loop Variables ----------//

        //List containing all information about url currently being loaded, and the browser it will be paired with




        private void Main_Loop()
        {
            var AllDone = false;

            while (AllDone == false)
            {

                //Send each browser in parallel through a series of processes
                Parallel.ForEach(CrawlerWebBrowsers, Single_Browser =>
                {
                    //While there are things to be doed, run this stuff
                    while (Form1.To_Process.Count > 0 | Form1.Processing.Count > 0 | Form1.Browser_Queue.Count > 0)
                    {
                        //If browser queue contains browser in question. run it
                        if (Form1.Browser_Queue.FindAll(x => x.Key == Single_Browser).Count > 0)
                        {
                            //Get Driver Package
                            var Driver_Package = Form1.Browser_Queue.Find(x => x.Key == Single_Browser);



                            //Remove from browser queue. While loop is because ofparallell duplicate execution problems
                            while (Form1.Browser_Queue.FindAll(x => x.Key == Single_Browser).Count > 0)
                            {
                                try
                                {
                                    Form1.Browser_Queue.RemoveAll(x => x.Key == Single_Browser);
                                }
                                catch (Exception)
                                {
                                }
                            }

                            if (Driver_Package.Value.ElementAt(0) != null)
                            {


                                //Collect variables
                                String Domain_String = Driver_Package.Value.ElementAt(0); // error found: argument null exception - this index was unset..
                                String URL_String = Driver_Package.Value.ElementAt(1);
                                String Domain_Code = Driver_Package.Value.ElementAt(2);
                                String URL_Code = Driver_Package.Value.ElementAt(3);
                                String MultiDomain = Driver_Package.Value.ElementAt(4);
                                String Source_ID = Driver_Package.Value.ElementAt(5);
                                String Anchor_Val = Driver_Package.Value.ElementAt(8);
                                String Mobile = Driver_Package.Value.ElementAt(9);

                                //Naviagtes
                                DateTime Start_Load = DateTime.Now;

                                // Pull out the right environment from the domain
                                //These 4 if statements make sure the crawler stays in the same environment the user is testing
                                //As these environments develop, make sure these blocks are changed appropriately
                                string Domain_Env = "";
                                if (Domain_String.Contains("g5demo"))
                                {
                                    Domain_Env = "g5demo";
                                }
                                if (Domain_String.Contains("g5cloud.me"))
                                {
                                    Domain_Env = "g5cloud.me";
                                }
                                if (Domain_String.Contains("real-staging.g5search"))
                                {
                                    Domain_Env = "real-staging.g5search";
                                }
                                if (Domain_String.Contains("pluto.g5search"))
                                {
                                    Domain_Env = "pluto.g5search";
                                }




                                //If for what ever reason this throws a null reference exception
                                //Remove this from the processing list.
                                //This could cause looping by this being stuck in the To_process list
                                //Look out for that, make need to make addition list modifications
                                //try
                                //{
                                Navigate(Driver_Package, Start_Load, Mobile);


                                //If the url was not taken out during the naviagtion process due to an error, run the rest of the functions
                               try
                                {
                                    if (Form1.Processing.FindAll(x => x.Value.ElementAt(2) == Domain_Code && x.Value.ElementAt(3) == URL_Code).Count > 0)
                                    {
                                        var Processing_Element = Form1.Processing.Find(x => x.Value.ElementAt(2) == Domain_Code && x.Value.ElementAt(3) == URL_Code);
                                        IWebDriver Browser_Still_Live = Processing_Element.Key;
                                        String Loaded_URL = Processing_Element.Value.ElementAt(6);
                                        String LoadTime = Processing_Element.Value.ElementAt(7);

                                        //Gathers URLs
                                        GatherURLs(Browser_Still_Live, Domain_String, URL_String, Domain_Code, MultiDomain, Mobile, Domain_Env);
                       
                                        //Do not rerun functions on rediects --Chris 4-22
                                        if (Loaded_URL == URL_String || URL_String + "/" == Loaded_URL || Loaded_URL + "/" == URL_String)
                                        {
                                            RunFunctions(Browser_Still_Live, Domain_String, URL_String, Domain_Code, URL_Code, Source_ID, LoadTime, Anchor_Val, Loaded_URL, Domain_Env);
                                        }
                                        else
                                        {
                                            
                                        }


                                        String[] Processed_Package = new String[4] { Domain_String, URL_String, Domain_Code, URL_Code };
                                        Form1.Processed.Add(Processed_Package);
                                    }
                                }
                                catch (Exception)
                                {
                                }


                                //}
                                // catch (NullReferenceException)
                                //{
                                // Form1.Processing.Find(x => x.Value.ElementAt(2) == Driver_Package.Value.ElementAt(2) && x.Value.ElementAt(3) == Driver_Package.Value.ElementAt(3));
                                // Form1.To_Process.Find(x => x.ElementAt(2) == Driver_Package.Value.ElementAt(2) && x.ElementAt(3) == Driver_Package.Value.ElementAt(3));
                                // Form1.Processed.Add(Driver_Package.Value);

                                // }

                            }

                            //*** DOM **
                            //This is the kind of list edits we need
                            //Remove from processing
                            while (Form1.Processing.FindAll(x => x.Key == Single_Browser).Count > 0)
                            {
                                Form1.Processing.RemoveAll(x => x.Key == Single_Browser);
                            }

                            //Once all done, Add back to open browsers
                            Form1.OpenBrowsers.Add(Single_Browser);


                        }


                        //Sleep so this doesnt run like crazy
                        System.Threading.Thread.Sleep(100);
                    }


                });

                /* Should not be needed. In order for loop to end in the first place To_Process and Processing must be zero
if (Form1.To_Process.Count < 1)
{
Form1.Processing.Clear();
AllDone = true;
}
*/
                //All done still needs to be set to quit the browsers doe
                AllDone = true;

            }

        }



        private void Navigate(KeyValuePair<IWebDriver, String[]> DriverPackage, DateTime Start_Load, String Mobile)
        {
            try
            {

                //Extract url and domain code
                String Domain_Code = DriverPackage.Value.ElementAt(2);
                String URL_Code = DriverPackage.Value.ElementAt(3);
                String Loaded_URL = "";
                if (Mobile != "true")
                {
                    //Navigate Driver to desired url
                    DriverPackage.Key.Navigate().GoToUrl(DriverPackage.Value.ElementAt(1));
                    //Get loaded URL
                    Loaded_URL = DriverPackage.Key.Url;

                }
                else
                {
                    //Navigate Driver to desired url

                    DriverPackage.Key.Navigate().GoToUrl("http://" + Form1.Server + "/g5quality_2.0/test_area.php?url=" + DriverPackage.Value.ElementAt(1) + "&domain=" + DriverPackage.Value.ElementAt(0));
                    //Get loaded URL
                    Loaded_URL = DriverPackage.Value.ElementAt(1);
                    DriverPackage.Key.Manage().Window.Size = new System.Drawing.Size(600, 900);
                    IJavaScriptExecutor js = DriverPackage.Key as IJavaScriptExecutor;
                    js.ExecuteScript("if((typeof switchToMobileLeads) == 'function'){$('a[href*=\"/leads/\"]').each(function(){ url = this.href.split('/'); url[4] = \"mobile_\" + url[4]; this.href = url.join('/'); });}");

                    if (DriverPackage.Key.FindElement(By.TagName("body")).Text.Contains("You are being") && DriverPackage.Key.FindElement(By.TagName("a")).Text.Contains("redirected"))
                    {

                        Loaded_URL = DriverPackage.Key.FindElement(By.TagName("a")).GetAttribute("href");
                        DriverPackage.Key.Navigate().GoToUrl("http://" + Form1.Server + "/g5quality_2.0/test_area.php?url=" + Loaded_URL + "&domain=" + DriverPackage.Value.ElementAt(0));

                    }
                    DriverPackage.Key.Manage().Window.Size = new System.Drawing.Size(1080, 900);

                }


                //Set loaded URL in processing list where url_code and domain_code match
                Form1.Processing.Find(x => x.Value.ElementAt(2) == Domain_Code && x.Value.ElementAt(3) == URL_Code).Value.SetValue(Loaded_URL, 6);
                DriverPackage.Value.SetValue(Loaded_URL, 6);
                //Get loaded time
                String LoadTime = (Math.Round((DateTime.Now - Start_Load).TotalSeconds, 2)).ToString();
                //Set Load Time
                Form1.Processing.Find(x => x.Value.ElementAt(2) == Domain_Code && x.Value.ElementAt(3) == URL_Code).Value.SetValue(LoadTime, 7);

            }
            catch (Exception exc)
            {
                //Fix in response to storquests modal breaking the domain scraper --Chris --2/3/14
                if (exc.Message == "Modal dialog present")
                {
                }
                else
                {
                    if (Form1.Processing.FindAll(x => x.Value.ElementAt(2) == DriverPackage.Value.ElementAt(2) && x.Value.ElementAt(3) == DriverPackage.Value.ElementAt(3)).Count > 0)
                    {
                        var To_Remove = Form1.Processing.Find(x => x.Value.ElementAt(2) == DriverPackage.Value.ElementAt(2) && x.Value.ElementAt(3) == DriverPackage.Value.ElementAt(3)); // error found Null reference exception
                        Form1.Processing.Remove(To_Remove);
                    }
                }
            }
        }

        private void GatherURLs(IWebDriver BrowserGetLinks, String Domain, String Source_Url, String Domain_Code, String MultiDomain, String Mobile, String Domain_Env)
        {
            String Browser_String = "";
            String Anchor = "";
            String Domain_Adjust = Domain.Replace("https://", "");
            Domain_Adjust = Domain_Adjust.Replace("http://", "");
            Domain_Adjust = Domain_Adjust.Replace("www.", "");
            String Browser_Adjust = "";



            List<IWebElement> LinksOnPage = new List<IWebElement>();
            //Get list of URLs on page
            try
            {
                LinksOnPage = BrowserGetLinks.FindElements(By.TagName("a")).ToList(); //error found INVALID OPERATION EXCEPTION
            }
            catch (Exception)
            {
                LinksOnPage = new List<IWebElement>();
                LinksOnPage = BrowserGetLinks.FindElements(By.TagName("a")).ToList();
            }
            foreach (IWebElement URL in LinksOnPage)
            {
                try
                {
                    //Initially allow the link to be valid
                    Boolean LinkValid = true;
                    //Get href
                    Browser_String = URL.GetAttribute("href");
                    Anchor = URL.Text;

                    //Adjust the url in the same way we did with the domain to allow for
                    //transfers from http:// to https://
                    Browser_Adjust = Browser_String.Replace("https://", "");
                    Browser_Adjust = Browser_Adjust.Replace("http://", "");
                    Browser_Adjust = Browser_Adjust.Replace("www.", "");
                    String Backup = Browser_Adjust;
                    /*We will be checking to see if the two domains are equal by taking a substring of the URL
            * If the URL found after being adjusted for http:// and www.
            * is shorter than the adjusted domain, it can not be an extention of that domain
            * and should be ignored
            */
                    if (Browser_Adjust.Length >= Domain_Adjust.Length)
                    {
                        //Take a substring of the URL of the same length as the domain
                        Browser_Adjust = Browser_Adjust.Substring(0, (Domain_Adjust.Length));
                        // Checking for equality, we are assuring that the two domains are equal.
                        if (Browser_Adjust != Domain_Adjust)
                        {
                            LinkValid = false;
                        }
                    }
                    else
                    {
                        LinkValid = false;
                    }


                    /*
* ------ This Code was replaced by the above code ^^ ------ Chris 4-21
*
//if url doesnt not contain domain do not include link
if (Browser_String.Contains((Domain.Replace("http://www.", ""))) == false)
{
LinkValid = false;
}
*/

                    /* ---------- lets try to run single location links within an umbrella domain--------- */
                    /* such as: mbkseniorliving.com.g5demo.com/senior_living/Fremont_CA/zip_94536/mbk_senior_living/10300 -- */
                    /* DISCLAIMER - This peice of code is subject to changes. The format taken here only aplies to Websites on the
                    * G5 Platform as of 6/28/14. */

                    /* start by removing the training slash  */
                    string Umbrella_Domain = Domain;
                    string no_slash_Domain = Umbrella_Domain.Remove(Umbrella_Domain.Length - 1);
                    // split Domain by / 
                    char[] delimiterChars = { '/' };
                    string[] Domain_parts = no_slash_Domain.Split(delimiterChars);
                    string raw_domain = Domain_parts[2];
                    string true_raw_domain = raw_domain.Replace("www.", "");

                    // if the last character in the domain is a number? then we have a Domain with a store id 
                    var last_char = no_slash_Domain[no_slash_Domain.Length - 1];
                    string last_string = last_char.ToString();
                    double num;

                    
                    if (double.TryParse(last_string, out num) == true)
                    {
                        // The domain splitted into parts above makes the final piece in the splitted array the store id, bingo! 
                        var g5_store_id = Domain_parts.Last();

                        if (Browser_String.Contains(g5_store_id) && Browser_String.Contains(Domain_Env) && Browser_String.Contains(true_raw_domain))
                        {
                            LinkValid = true;
                        }
                        else
                        {
                            LinkValid = false;
                        }
                    }
                     
                    

                    //But if they are checking for multidomain sites check for g5demo or appropriate env in url string

                    if (MultiDomain == "true")
                    {

                        if (Domain.Contains(Domain_Env) && Browser_String.Contains(Domain_Env))
                        {
                            LinkValid = true;
                        }
                        else
                        {
                            LinkValid = false;
                        }


                    }
                    //We are avoiding fullsites urls for mobile. Was causing duplication problems. Chris -4/2/14

                    if (Mobile == "true")
                    {
                        if (Browser_String.Contains("fullsite"))
                        {
                            LinkValid = false;
                        }

                    }



                    //Make sure it passes the link constaints
                    if (Form1.All_URLs.FindAll(x => x.Key == Domain_Code && x.Value.Replace("http://", "").Replace("www.", "").Replace("https://", "") == Backup).Count < 1)
                    {
                        foreach (string Issue in Form1.LinkConstraints)
                        {
                            if (Browser_String.Contains(Issue))
                            {
                                LinkValid = false;
                            }
                        }
                        //If at this point the link is still valid add it to que
                        if (LinkValid == true)
                        {
                            String Random_Code = "";
                            long Additive = 0;
                            int LengthOfCode = 15;
                            long microseeconds = DateTime.Now.Ticks;
                            int Processed_Count = Form1.Processed.Count + 5;
                            int To_Process_Int = Form1.To_Process.Count + 3;
                            while (LengthOfCode > 0)
                            {
                                Additive = (((Additive + microseeconds) * Processed_Count + 5) / LengthOfCode) / To_Process_Int;
                                LengthOfCode = LengthOfCode - 1;
                                microseeconds = DateTime.Now.Ticks;
                            }

                            Random_Code = Additive.ToString();
                            Form1.All_URLs.Add(new KeyValuePair<string, string>(Domain_Code, Browser_String));
                            Form1.To_Process.Add(new String[8] { Domain, Browser_String, Domain_Code, Random_Code, MultiDomain, Source_Url, Anchor, Mobile });
                            
                        }
                    }
                }
                catch (Exception)
                {
                }
            }
        }

        private void RunFunctions(IWebDriver Hand_Off, String Domain_String, String URL_String, String Domain_Code, String URL_Code, String Source_ID, String LoadTime, String Anchor, String Loaded_URL, String Domain_Env)
        {
            
            //----Grab Some Information That Will Be Used Frequently Here----//

            //Get All Elements On Page
            List<IWebElement> AllElements = new List<IWebElement>();
            AllElements = Hand_Off.FindElements(By.TagName("html")).ToList(); //error found WebDriver Exception
            //Get All Text On Page
            String AllTextOnPage = "";
            String AllTextOnPage2 = "";
            try
            {

                foreach (IWebElement GetText in AllElements)
                {
                    AllTextOnPage = AllTextOnPage + GetText.Text.ToLower(); // error found stale element reference exception found // this times out after 60 waiting for server response
                    AllTextOnPage2 = AllTextOnPage2 + GetText.Text;
                }
            }
            catch (StaleElementReferenceException) // this try catch
            {
                //Maybe route away from spelling elements should this be thrown. Would save some time -Chris
            }
            //Seperate Into Individual Words On Page
            List<String> AllWordsTemp = new List<string>();
            AllWordsTemp = AllTextOnPage2.Split(' ').ToList();
            //Get Title
            String Page_Title = Hand_Off.Title;
            //Get All Links on page and add to list
            List<IWebElement> AllLinks = new List<IWebElement>();
            try
            {
                foreach (var Element in AllElements)
                {
                    var HtmlLinks = Element.FindElements(By.TagName("a")).ToList();
                    foreach (var Link in HtmlLinks)
                    {
                        AllLinks.Add(Link);
                    }
                }
            }
            catch (StaleElementReferenceException)
            {
            }
            //Get Images
            var Images = new List<IWebElement>();
            Images = Hand_Off.FindElements(By.TagName("img")).ToList();
            //Get Meta
            var meta_desc = "";
            var list_metas = Hand_Off.FindElements(By.TagName("meta")).ToList();
            var list_scripts = Hand_Off.FindElements(By.TagName("script")).ToList();
            try
            {
                foreach (var single_meta in list_metas)
                {
                    if (single_meta.GetAttribute("name") == "description")
                    {
                        meta_desc = single_meta.GetAttribute("content");
                    }
                }
            }
            catch (Exception)
            {
            }


            //---------- Run All Functions ---------------//
    
            //Find Broken Page Class
            Broken_Pages Check_For_Broken_Pages = new Broken_Pages(Domain_String, URL_String, Source_ID, Domain_Code, URL_Code, Page_Title, AllTextOnPage);

            //Find Duplicate Title Class
            Duplicate_Title Check_For_Duplicate_Title = new Duplicate_Title(Domain_String, URL_String, Source_ID, Domain_Code, URL_Code, Page_Title);


            //Find Linking Issues
            LinkingIssues Linking_Issues = new LinkingIssues(Domain_String, URL_String, Source_ID, Domain_Code, URL_Code, AllLinks, URL_String);

            //Find Broken HTML Entities
            TextFormattingErrors Format_Errors = new TextFormattingErrors(Domain_String, URL_String, Source_ID, Domain_Code, URL_Code, AllTextOnPage, Page_Title);

            //Check for Double Spaces
            Double_Space Double_Spaces = new Double_Space(Domain_String, URL_String, Source_ID, Domain_Code, URL_Code, AllTextOnPage);

            //Image Issues
            //ImageErrors FindImageErrors = new ImageErrors(Domain_String, URL_String, Source_ID, Domain_Code, URL_Code, AllTextOnPage, Images);

            //Repetitive Text
            RepetitiveText Check_For_Repetitive_Text = new RepetitiveText(Domain_String, URL_String, Source_ID, Domain_Code, URL_Code, AllTextOnPage);

            //Don't throw errors about Google analytics codes if the page is broken
            if (!(Check_For_Broken_Pages.Code_Found == true && Check_For_Broken_Pages.Response_Found == true))
            {
                GA_Check Check_For_GA = new GA_Check(Domain_String, URL_String, Source_ID, Domain_Code, URL_Code, list_scripts);
            }
            else
            {
                //If it is broken, don't return a meta description... -Chris 4/14
                meta_desc = "";
            }

            //Check spelling
            AllWordsTemp = AllWordsTemp.Distinct().ToList();
            Spell_Check Check_Spelling = new Spell_Check(Domain_String, URL_String, Source_ID, Domain_Code, URL_Code, AllWordsTemp);

            /*Check for errors in the .xml sitemap.
            //This is not going to work until ALL urls have been crawled soo...
            //Maybe put this in the Find_Finished() class and run the funciton there and then set to done after that task reports a finished status
            if (URL_String == Domain_String && Domain_Env == "")
            {
                // SiteMapCheck Check_For_XML_Errors = new SiteMapCheck(Domain_String, URL_String, Source_ID, Domain_Code, URL_Code, Page_Title, AllTextOnPage);
            }
            */

          
            //Pass to data send off
            DB_Testing(Domain_String, URL_String, Source_ID, Domain_Code, URL_Code, Page_Title, LoadTime, meta_desc, Anchor, Loaded_URL);

        }




        private void DB_Testing(String Domain, String URL, String Source_ID, String Domain_Code, String URL_Code, String PageTitle, String LoadTime, String Meta, String Anchor, String Loaded_URL)
        {




            String Time = DateTime.Now.ToString("yyyyMMddHHmmssffff");

            //Strip for sql injection
            Domain = Domain.Replace("'", "");
            URL = URL.Replace("'", "");
            URL_Code = URL_Code.Replace("'", "");
            Source_ID = Source_ID.Replace("'", "");
            Domain_Code = Domain_Code.Replace("'", "");
            PageTitle = PageTitle.Replace("'", "");
            Meta = Meta.Replace("'", "");
            Anchor = Anchor.Replace("'", "");
            Loaded_URL = Loaded_URL.Replace("'", "");



            String ValueString = "('" + Domain + "', '" + URL + "', '" + Loaded_URL + "', '" + Source_ID + "', '" + Domain_Code + "', '" + URL_Code + "', '" + PageTitle + "', '" + Time + "', '" + LoadTime + "', '" + Meta + "', '" + Anchor + "');";
            String Query = "insert into alldata(Domain, ID, redirects_to, SourceID, Code, URL_Code, Title, Time, LoadTime, Meta, A_Text) values" + ValueString;
            Form1.DataPush.Add(Query);
        }



    }
}