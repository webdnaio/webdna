# WebDNA.io 

WebDNA.io is non existing SaaS app. 
Our runway was too short. We've made a ton of mistakes, which I’d like to share with others. Even if only one person could use this knowledge and avoid the traps we fell into, we would be happy that I was able to help. 

Since nobody could benefit from our work, WebDNA.io team decided to relase sources of the application to open public domain. 

You can see how WebDNA look like here 
http://blog.webdna.io/check-and-monitor-backlinks/

WebDNA user guide 
http://webdna.io/ManualWebDNA.pdf

## About

WebDNA.io was a self-learning big data Software as a Service (SaaS) tool designed to facilitate webmasters’ tasks and help marketing specialists audit their search engine optimization (SEO) services. Engine collects information about website’s external links and compares it with industry benchmarks in order to assess the quality of links. The results are precise recommendations which inbound links to disavow and how to adjust the website to the current search engine policy.

WebDNA was an immediate help in lifting the ban on your website but also a long-term solution for monitoring the current situation of your backlinks to prevent the negative consequences. On a regular basis users receive reports with the newest analysis of the backlinks and can take any actions accordingly before the problem escalates.

WebDNA primarily helps in the times of need when the quality of your links is not at its best. After stopping the fire, that is lifting the ban or getting rid of the negative links, the tool will let you keep an eye on the situation with your website backlinks. However, it can also help you to make your situation even better by analysing the backlinks of your competitors to find the right websites of high quality for your links. As a byproduct, you will also learn more about your business area and understand better your competitors. 


## Sybilla machine learning 

Sybilla was a part of WebDNA that use ML model to detect positive and non positive backlinks. 

Sources of Sybilla can be found here 
https://github.com/webdnaio/webdna-sybilla


Thanks to Sybilla algorithm, links assessment effective. Right now we’re measuring thirty-something parameters for each URL address. 
They give the basis for the quality assessment which is then sent to you.

Sybilla divides websites into three categories: negative, suspicious, positive.

Sybilla is an original complex algorithm which takes into account the following parameters when assessing any HTML document:
- meta tags quality; for example, the length of tag title
- the structure of the HTML document and its quality
- the number and type of links on a given website
- the number of images in the document
- the distribution of links in each part of the website
- HTM2TXT ratio
- the amount of text found on the website
- loading time and the status code of the website
- domain authority
- the analysis of the website for malware and viruses

The system collects and calculates also some additional parameters needed for the correct analysis (a secret know-how of the creators). Those data are assessed and help to:
- evaluate the profile and the quality of links;
- make decisions whether it’s worth to keep the links on a given website;
- evaluate the links of your competition;
- search for the quality websites where your links could be placed.


WebDNA is licensed under LGPL
https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html

Follow our Twitter 
https://twitter.com/WebDNAio
