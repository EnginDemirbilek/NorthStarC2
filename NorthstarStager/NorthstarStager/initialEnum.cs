using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace NorthStarStager
{


    /*
    <explanation>
    Class that responsible for retrieving information about environment.
    Variable names are directly associated with information will holded.
    <variable>isWaitTimeManuellySetted</variable> is used for setting communication interval time to fixed value.
    As default it is a random value between 8-23 seconds.
    </explanation>
    */
    class initialEnum
    {

        public string mName;
        public string userName;
        public string osVer;
        public string workingDir;
        public string executablePath;
        public string clientID;
        public string processName;
        public string xorKey;
        public int waitTime;
        public int processId;
        public bool isAdmin;
        public bool isWaitTimeManuallySetted = false;
        
       

        public initialEnum()
        {

            _setEnvVars();
            osVer = _setOsVer();
            setRandomWaitTime();
        }


        /*
        <explanation>
        Sets interval for retrieveing command from controller server.
        As default it is a random value between 8-23 seconds.
        </explanation>
        */
        public void setRandomWaitTime()
        {

            Random random = new Random();
            try
            {
            #pragma warning disable SCS0005 // Weak random generator
             waitTime = random.Next(2, 6) * 1000;
            #pragma warning restore SCS0005 // Weak random generator
            }
            catch
            {
                waitTime = 10000;
            }
        }




        /*
       <explanation>
        Retrieves environment information.
        mName stands for machine name which is retrieved from built-in <class>WindowsIdentity</class>
        To check if user is admin built-in <method>IsInRole</method> of <class>WindowsPrincipal</class> is used. It returns true or false.
        To get directory and process informations built-in <class>Directory</class> and <class>Process</class> is used.
       </explanation>
       */
        private void _setEnvVars()
        {
            mName = Environment.MachineName;
            userName = System.Security.Principal.WindowsIdentity.GetCurrent().Name;
            using (System.Security.Principal.WindowsIdentity identity = System.Security.Principal.WindowsIdentity.GetCurrent())
            {
                System.Security.Principal.WindowsPrincipal principal = new System.Security.Principal.WindowsPrincipal(identity);
                isAdmin = principal.IsInRole(System.Security.Principal.WindowsBuiltInRole.Administrator);
            }
            workingDir = System.IO.Directory.GetCurrentDirectory();
            processId = System.Diagnostics.Process.GetCurrentProcess().Id;
            processName = System.Diagnostics.Process.GetCurrentProcess().ProcessName;
            executablePath = System.Diagnostics.Process.GetCurrentProcess().MainModule.FileName;
        }


        /*
        <explanation>
        Retrieves product name from Registery keys.
        It returns a value as : "Windows 10 Home"
        </explanation>
         */
        private string _setOsVer()
        {
            return (string)Microsoft.Win32.Registry.GetValue(@"HKEY_LOCAL_MACHINE\SOFTWARE\WOW6432Node\Microsoft\Windows NT\CurrentVersion", "ProductName", null);

        }


        //<explanation>returns machine name</explanation>
        public string _getmName()
        {

            return mName;
        }


        //<explanation>returns user name</explanation>
        public string _getuserName()
        {

            return mName;
        }


        //<explanation>returns operating system verions</explanation>
        public string _getOsVer()
        {
            return osVer;
        }


        //<explanation>returns if current privileges are administrator privileges</explanation>
        public bool _getisAdmin()
        {
            return isAdmin;

        }


    }
}
