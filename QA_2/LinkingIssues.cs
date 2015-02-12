using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using OpenQA.Selenium;

namespace QA_2
{
    class LinkingIssues
    {
        public String LinkingIssueReturn = "";
        public LinkingIssues(String Domain, String URL, String SourceUrl, String Domain_Code, String URL_Code,List<IWebElement> Links, String This_Url)
        {

            if(This_Url.Contains("general"))
            {
                    LinkingIssueReturn = "General Found In URL";
                    String ValueString = "('" + Domain + "', '" + URL + "', '" + SourceUrl + "', '" + Domain_Code + "', '" + URL_Code + "', 'LinkingIssue', '" + LinkingIssueReturn + "')";
                    String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                    Form1.DataPush.Add(Query);
            }

            if (This_Url.Contains("site_map") | This_Url.Contains("sitemap"))
            {
                if (Links.FindAll(x => x.Text.ToLower().Contains("index")).Count > 0)
                {
                    LinkingIssueReturn = "Index Found On Sitemap";
                    String ValueString = "('" + Domain + "', '" + URL + "', '" + SourceUrl + "', '" + Domain_Code + "', '" + URL_Code + "', 'LinkingIssue', '" + LinkingIssueReturn + "')";
                    String Query = "insert into errors(Domain, URL, SourceUrl, Domain_Code, URL_Code, type, message) values" + ValueString;
                    Form1.DataPush.Add(Query);
                }
            }
          

        }

    }
}
