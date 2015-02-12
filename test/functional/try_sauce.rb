require 'rubygems'
require 'selenium-webdriver'


caps = Selenium::WebDriver::Remote::Capabilities.new
caps["platform"] = "Windows 8.1"
caps["browserName"] = "internet explorer"
caps["version"] = "11"
caps["name"] = "testing with sauce labs"

driver = Selenium::WebDriver.for(:remote,
	  url: "http://#{ENV['SAUCE_USERNAME']}:#{ENV['SAUCE_ACCESS_KEY']}@ondemand.saucelabs.com:80/wd/hub",
	  desired_capabilities: caps)

driver.get "http://steadyfilter.com/home.php"
domain = driver.find_element(:id => "domain")
test_class = domain.attribute("class")

if test_class.include? "form-control"
	puts "pass"
else
	puts "fail"
end

driver.quit


