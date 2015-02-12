using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using OpenQA.Selenium;
using OpenQA.Selenium.Firefox;

namespace QA_2
{
    class Browser_Profiles
    {
        public Browser_Profiles(String Extension)
        {
            //Set user agent (In this case iphone 5 with ios 7.0
            string useragent = "Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53";
            
            string mobilenumber = "5555555555";







            if (File.Exists(Extension))
            {

                Form1.Mobile_Profile.AddExtension(Extension);


                Console.WriteLine("Modifyheaders.xpi file not found");
                Form1.Mobile_Profile.SetPreference("modifyheaders.config.active", true);
                Form1.Mobile_Profile.SetPreference("modifyheaders.config.alwaysOn", true);
                Form1.Mobile_Profile.SetPreference("modifyheaders.headers.count", 2);
                Form1.Mobile_Profile.SetPreference("modifyheaders.headers.action0", "Add");
                Form1.Mobile_Profile.SetPreference("modifyheaders.headers.name0", "User-Agent");
                Form1.Mobile_Profile.SetPreference("modifyheaders.headers.value0", useragent);
                Form1.Mobile_Profile.SetPreference("modifyheaders.headers.enabled0", true);
                Form1.Mobile_Profile.SetPreference("modifyheaders.headers.action1", "Add");
                Form1.Mobile_Profile.SetPreference("modifyheaders.headers.name1", "x-up-subno");
                Form1.Mobile_Profile.SetPreference("modifyheaders.headers.value1", mobilenumber);
                Form1.Mobile_Profile.SetPreference("modifyheaders.headers.enabled1", true);
                IWebDriver browser = new FirefoxDriver();

                
                

                browser.Manage().Window.Size = new System.Drawing.Size(600, 800);

                browser.Navigate().GoToUrl("http://192.168.2.44/g5quality_2.0/test_area.php?url=http://www.metrostorage.com&domain=http://www.metrostorage.com/");

                browser = new FirefoxDriver(Form1.Mobile_Profile);

                IJavaScriptExecutor js = browser as IJavaScriptExecutor;


                js.ExecuteScript("if((typeof switchToMobileLeads) == 'function'){$('a[href*=\"/leads/\"]').each(function(){ url = this.href.split('/');  url[4] = \"mobile_\" + url[4]; this.href = url.join('/'); });}");

            }
        }

    }
}
