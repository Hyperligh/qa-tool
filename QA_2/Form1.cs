using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using MySql.Data.MySqlClient;
using OpenQA.Selenium;
using OpenQA.Selenium.Firefox;
using OpenQA.Selenium.PhantomJS;

namespace QA_2
{
    public partial class Form1 : Form
    {


        /*
         * INTRUSCTIONS FOR RUNNING LOCALLY
         *  -The idea is that the whole application will be ran locally
         *  so we will need a few pieces to be functional in order for that to
         *  happen
         *      - Locally you should be running the php site of the QAtool
         *      - There should be a database running mysql set up
         *      - You will need to have visual studio to have gotten this far
         *  SETUP
         *      -First switch the string to localhost or 127.0.0.1
         *      -Make sure your database name matches your local db.
         *      
        */


        //LOCAL SETTTINGS
        public static string Server = "127.0.0.1";
        public static string Db_User = "root";

        //Server Setings
        //public static string Server = "192.168.2.44";
        public static string Port = "3306";
        //public static string Db_User = "app_user1";
        public static string Db_Password = ""; 

        //Database Settings
        public static string DataBaseName = "g5_qa"; 
        public static string MainTableName = "alldata";
        //Connections
        public static MySqlConnection ConnectToPullData;
        //Lists Containing Information Needed to write to database
        public static List<String>Column_Names = new List<string>();


        //Configurations 
        int Browsers = 4;

        public static FirefoxProfile Mobile_Profile = new FirefoxProfile();

        //Lists of parameters for scripts (**PART OF ADDING NEW SCRIPT**.... (more info under 'script related functions' below))
        public static List<String> MetaIssueVariables = new List<string>();  //% string vs String, i don't see a difference
                                                                             //A: There is no difference as far as I know. I tend to do captials, and visual studio completes my lines with lower case 'string'
                                                                             //   I also use it to make the code look pretty, which is arguably as, if not more important than the functionality
        public static List<String> LinkConstraints = new List<String>();
        public static List<String> FilteredLinkTypes = new List<String>();
        public static List<String> SpellingMods = new List<string>();
        public static List<String> LettersForRandom = new List<string>();
        public static List<KeyValuePair<String, String>> ErrorResponseCodes = new List<KeyValuePair<string, string>>();


        // Dynamic lists that will handle data to be passed from 'Data_Handler' to various functions
        public static List<String> DataPush = new List<string>();
        //This will be used by the 'Moniter_Domain' class and will contain the domain and code of the request 
        public static List<KeyValuePair<KeyValuePair<String, String>, String>> Queued_Domain = new List<KeyValuePair<KeyValuePair<String, String>, String>>();
        

        /* ***This is the list that will hold data telling the browsers where to go***
         * It will hold the following information
         * Domain, url, domain_code, url_code, multidomain, source_url
        */
        public static List<String[]> To_Process = new List<String[]>();
        //List containing all information about url currently being loaded, and the browser it will be paired with
        public static List<KeyValuePair<IWebDriver, String[]>> Processing = new List<KeyValuePair<IWebDriver, String[]>>();



        public static List<IWebDriver> OpenBrowsers = new List<IWebDriver>();
        public static List<KeyValuePair<IWebDriver, String[]>> Browser_Queue = new List<KeyValuePair<IWebDriver, string[]>>();

        private monitor_domains_new Monitor_Class;
        private Data_Handler Collect_Class;

        //BackGround processes
        public static BackgroundWorker Data_Handling;
        public static BackgroundWorker Moniter;
        public static BackgroundWorker WatchMan;





        /* The 'Processed' list will hold the information on what urls have already been loaded and processed
         * This will be used by the processing unit to detect which urls it has already ran
         * It will also be used by the monitering system 
         * The monitoring system will, for each domain, check to see if any urls containing that domain code exist in the To_Process list. If not, that domain is done
         * ^^ In addition to having no urls present in the To_Process, there must also be at least 1 url in the 'Processed' list... Think about it...
         * 
          **INFO CONTAINED**
         Domain, URL, Domain_Code, URL_Code, MultiDomain, Source_URL, Loaded_URL, Load_Time
        */
        public static List<String[]> Processed = new List<String[]>();
        
        //All_URLs will be used to avoid duplicates being added to the To_Proocess and Processing lists
        public static List<KeyValuePair<String, String>> All_URLs = new List<KeyValuePair<String, String>>();




        public Form1()
        {
            Setup();
            InitializeComponent();

        }

        private void button1_Click(object sender, EventArgs e)
        {
            this.Visible = false;


            //Testing_Function();
            //MessageBox.Show("tests done");



            Background_Processing();
            Start_WatchMan();
            Main_Loop();
        }


        public void Setup()
        {
            Set_Connections();
            Column_Settings();
            Write_Columns();
            Script_Parameters();

        }

        //----------- Settings Functions -----------//

        //Here settings that will be used by the rest of the application are set
        //All are set in for one for ease of access and adjustment
        private void Set_Connections()
        {
            //**Working**

            ConnectToPullData = new MySqlConnection("Server=" + Server + ";Port=" + Port + ";Database=" + DataBaseName + ";Uid=" + Db_User + ";Password=" + Db_Password);
        }
        //Function Setup and Settings (**PART OF ADDING NEW SCRIPT**)
        private void Column_Settings()
        {
            //**Working**

            Column_Names.Add("Domain");

            Column_Names.Add("SourceID");

            Column_Names.Add("ID");

            Column_Names.Add("redirects_to");

            Column_Names.Add("LoadTime");

            Column_Names.Add("Code");

            Column_Names.Add("URL_Code");

            Column_Names.Add("Time");

            Column_Names.Add("Title");

            Column_Names.Add("Meta");

            Column_Names.Add("A_Text");

        }
        //This will automatically create a column for each function in the database if it doesn't already exist
        private void Write_Columns()
        {
            //**Working**

            //Open database connection
            ConnectToPullData.Open();
            //For each column (script) there should exist a column for it to write it's returned data to
            foreach (String Column in Column_Names)
            {
                //The try statement exists because many of the columns already exist. So an error will be thrown, but it can be ignored
                //% could try "add unique" instead of ADD to avoid the try catch 
                try
                {
                    MySqlCommand cmd = new MySqlCommand("ALTER TABLE " + MainTableName + " ADD " + Column + " VARCHAR(2000);", ConnectToPullData);
                    cmd.ExecuteNonQuery();
                    cmd.Dispose();
                }
                catch (Exception)
                {
                }
            }
            //Close and dispose of connect
            ConnectToPullData.Close();
            ConnectToPullData.Dispose();       //% what is the prupose of this practice

            //A: Closing the connection is important because if you are executing many commands and leave all open connections it will build up and slow down the server speed.

        }
        // Script Related Settings (**PART OF ADDING NEW SCRIPT**) 
        private void Script_Parameters()
        {
            // **Working**

            /*If a script has parameters that go along with it, enter them here
             * Make a list or whatever data structure need (more info up top ^^) to hold information
             * make in public static type so it can be accessed from all parts of application
            */


            // ---- META ISSUES SETTINGS --- //
            MetaIssueVariables.Add("store.city");
            MetaIssueVariables.Add("branded_name");
            MetaIssueVariables.Add("store.state");
            MetaIssueVariables.Add("driving_directions");
            MetaIssueVariables.Add("remote_store_url");
            MetaIssueVariables.Add("facility_page_link");
            MetaIssueVariables.Add("state_long");
            MetaIssueVariables.Add("store.zip");
            MetaIssueVariables.Add("marketing_number");
            MetaIssueVariables.Add("telephone_number ");
            MetaIssueVariables.Add("store.id");
            MetaIssueVariables.Add("special_text");
            MetaIssueVariables.Add("}");
            MetaIssueVariables.Add("{");

            // ---- FILTERED LINK TYPE SETTINGS --- //
            FilteredLinkTypes.Add("/leads/");
            FilteredLinkTypes.Add("site_map");

            // ---- LINK CONSTRAINT SETTINGS --- //
            LinkConstraints.Add("#");
            LinkConstraints.Add("survey");
            LinkConstraints.Add("blog");
            LinkConstraints.Add("@");
            //File types
            LinkConstraints.Add(".xml");
            LinkConstraints.Add(".doc");
            LinkConstraints.Add(".enw");
            LinkConstraints.Add(".pdf");
            LinkConstraints.Add(".xls");
            LinkConstraints.Add(".bib");
            LinkConstraints.Add(".rtf");
            LinkConstraints.Add(".bib");
            LinkConstraints.Add(".zip");
            LinkConstraints.Add(".bib");

            LinkConstraints.Add("mailto");
            LinkConstraints.Add("mobile_mobile");
            LinkConstraints.Add("/locale/es-ES");

            // ---- Spelling Modification Settings --- //
            SpellingMods.Add("online");
            SpellingMods.Add("offline");
            SpellingMods.Add("healthcare");
            SpellingMods.Add("de");
            SpellingMods.Add("proactively");
            SpellingMods.Add("onsite");
            SpellingMods.Add("offsite");
            SpellingMods.Add("blog");
            SpellingMods.Add("app");
            SpellingMods.Add("bodacious");
            SpellingMods.Add("smartphone");
            SpellingMods.Add("widescreen");
            SpellingMods.Add("fundraising");
            SpellingMods.Add("lifecycle");
            SpellingMods.Add("unsubscribe");
            SpellingMods.Add("playlist");
            SpellingMods.Add("dialogue");
            SpellingMods.Add("boxspring");
            SpellingMods.Add("oversized");
            SpellingMods.Add("sundeck");
            SpellingMods.Add("theatre");
            SpellingMods.Add("sunroom");
            SpellingMods.Add("wifi");
            SpellingMods.Add("texting");
            SpellingMods.Add("microbrew");
            SpellingMods.Add("cardio");
            SpellingMods.Add("d�cor");
            SpellingMods.Add("tai");
            SpellingMods.Add("entree");
            SpellingMods.Add("entrees");
            SpellingMods.Add("countertops");
            SpellingMods.Add("townhomes");
            SpellingMods.Add("reservable");
            SpellingMods.Add("townhome");
            SpellingMods.Add("online");
            SpellingMods.Add("internet");
            SpellingMods.Add("faux");
            SpellingMods.Add("affordability");



            ErrorResponseCodes.Add(new KeyValuePair<string, string>("304", "not modified"));
            ErrorResponseCodes.Add(new KeyValuePair<string, string>("400", "bad request"));
            ErrorResponseCodes.Add(new KeyValuePair<string, string>("401", "unauthorized"));
            ErrorResponseCodes.Add(new KeyValuePair<string, string>("403", "forbidden"));
            ErrorResponseCodes.Add(new KeyValuePair<string, string>("404", "not found"));
            ErrorResponseCodes.Add(new KeyValuePair<string, string>("406", "not acceptable"));
            ErrorResponseCodes.Add(new KeyValuePair<string, string>("422", "unprocessable entity"));
            ErrorResponseCodes.Add(new KeyValuePair<string, string>("429", "too many requests"));
            ErrorResponseCodes.Add(new KeyValuePair<string, string>("500", "internal server error"));
            ErrorResponseCodes.Add(new KeyValuePair<string, string>("502", "bad gateway"));
            ErrorResponseCodes.Add(new KeyValuePair<string, string>("503", "service unavailable"));
            ErrorResponseCodes.Add(new KeyValuePair<string, string>("504", "timeout"));





        }
        private void Load_Browser_Profiles()
        {
            //Get the extension file for the firefox browser that allows it to modify headers
            var extension_file = new OpenFileDialog();
            //user prompted to select file
            extension_file.ShowDialog();

            Browser_Profiles set_profiles = new Browser_Profiles(extension_file.FileName);

        }

        //-------------------------------------------//


        private void Background_Processing()
        {

            //-------Collection of queued domains------//
            Data_Handling = new BackgroundWorker();
            //From here the background controller is assigned a function to run ('Data_Handling_Dowork' In This Case)
            Data_Handling.DoWork += new System.ComponentModel.DoWorkEventHandler(this.Data_Handling_DoWork);
            //Executes background controller
            Data_Handling.RunWorkerAsync();
            //----------------------------------------//

            //------- Monitors the progress of Domains (Codes) ------//
            Moniter = new BackgroundWorker();
            Moniter.DoWork += new System.ComponentModel.DoWorkEventHandler(this.Moniter_DoWork);
            Moniter.RunWorkerAsync();
            //----------------------------------------//

        }

        private void Start_WatchMan()
        {
            //----- Watchman ------//
            WatchMan = new BackgroundWorker();
            WatchMan.DoWork += new System.ComponentModel.DoWorkEventHandler(this.WatchMan_DoWork);
            WatchMan.RunWorkerAsync();
            //----- WatchMan -----//
        }


        private void Data_Handling_DoWork(object sender, DoWorkEventArgs e)
        {
            //The class that handles database interactions is called here (inside background controller function)

            //Class called here.
            Collect_Class = new Data_Handler();
            Collect_Class.Starter();
        }
        private void Moniter_DoWork(object sender, DoWorkEventArgs e)
        {
            Monitor_Class = new monitor_domains_new();
            Monitor_Class.Starter();
        }

        private void WatchMan_DoWork(object sender, DoWorkEventArgs e)
        {
            while(true)
            {
                System.Threading.Thread.Sleep(5000);
                DateTime NowTime = DateTime.Now;
                if (Monitor_Class.CheckIn.AddMinutes(4) < NowTime | Collect_Class.CheckIn.AddMinutes(4) < NowTime)
                {
                    Moniter.Dispose();
                    Data_Handling.Dispose();
                    Processing.Clear();
                    To_Process.Clear();
                    Queued_Domain.Clear();
                    Browser_Queue.Clear();
                    System.Threading.Thread.Sleep(5000);
                    Background_Processing();
                }
              




            }
        }


        //Main loop controls the browsers and processing in order to get full access to CPU
        public void Main_Loop()
        {
            do
            {
                //When something is found in the to process list, launch browsers, run process, and close browsers on completion
                if (To_Process.Count > 0)
                {
                    //--------------Load Up Browsers-------------//

                    List<IWebDriver> CrawlerWebBrowsers = new List<IWebDriver>();
                    int Browser_Count = Browsers;
                    while (Browser_Count > 0)
                    {
                        IWebDriver newdriver = new FirefoxDriver();

                        CrawlerWebBrowsers.Add(newdriver);
                        OpenBrowsers.Add(newdriver);
                        Browser_Count = Browser_Count - 1;

                    }

                    //-------------Launch Collection Process-----------//

                    process_new Launch_Process = new process_new(CrawlerWebBrowsers);

                    //-------------- Kill Browsers ------------//
                    OpenBrowsers.Clear();

                    Parallel.ForEach(CrawlerWebBrowsers, Single_Browser =>
                    {
                        try
                        {
                            Single_Browser.Quit(); //win32 exeption was unhadled ERROR FOUND   // clean fix was implemented
                        }
                        catch (Win32Exception)
                        {
                        }
                    });



                }
                System.Threading.Thread.Sleep(2000);
                

            }
            while (true);
        }


        private void Testing_Function()
        {


            Load_Browser_Profiles();
            MessageBox.Show("done");
        }

        private void Form1_Load(object sender, EventArgs e)
        {

        }



    }
}
