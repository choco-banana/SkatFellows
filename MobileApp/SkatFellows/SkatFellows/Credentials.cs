using System;
using System.IO;
using System.Collections.Generic;
using System.Text;

namespace SkatFellows
{
    public class Credentials
    {
        public Credentials()
        {
            string documentsPath = System.Environment.GetFolderPath(System.Environment.SpecialFolder.Personal);
            var filePath = System.IO.Path.Combine(documentsPath, "settings.txt");
            if (System.IO.File.Exists(filePath))
            {
                string[] credentialList = System.IO.File.ReadAllLines(filePath);
                RestUrl = credentialList[0];
                Username = credentialList[1];
                Password = credentialList[2];
            }
            else
            {
                var fs = new FileStream(filePath, FileMode.Create);
                fs.Dispose();
                RestUrl = "url";
                Username = "user";
                Password = "password";
                string[] credentialList = { RestUrl, Username, Password };
                File.WriteAllLines(filePath, credentialList);
            }
        }

        public string RestUrl { get; set; }
        public string Username { get; set; }
        public string Password { get; set; }
    }
}
