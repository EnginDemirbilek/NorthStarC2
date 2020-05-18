using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace NorthStarStager
{
    class Chiper
    {

        /*
         <explanation>
         Encrypt every outgoing output with XOR and base64 and decrpyt every output vice versa.
         </explanation> 
        */


        public string _base64encode(string clearString)
        {
            byte[] clearStringdata = System.Text.ASCIIEncoding.ASCII.GetBytes(clearString);
            string b64string = System.Convert.ToBase64String(clearStringdata);
            return b64string;
        }

        public string _base64decode(string encodedString)
        {
           
            string base64Decoded;
            byte[] data = System.Convert.FromBase64String(encodedString);
            base64Decoded = System.Text.ASCIIEncoding.ASCII.GetString(data);
            return base64Decoded;
        }

        public string _xorOps(string text, string key)
        {
                var createdString = new StringBuilder();

                for (int c = 0; c < text.Length; c++)
                    createdString.Append((char)((uint)text[c] ^ (uint)key[c % key.Length]));

                return createdString.ToString();
        }


    }
}
