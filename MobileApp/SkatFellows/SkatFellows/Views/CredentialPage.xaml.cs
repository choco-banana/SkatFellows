using System;
using System.IO;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace SkatFellows
{
	[XamlCompilation(XamlCompilationOptions.Compile)]
	public partial class CredentialPage : ContentPage
	{
		public CredentialPage ()
		{
			InitializeComponent ();
		}

        async void SaveCredentials(object sender, EventArgs e)
        {
            var settings = (Credentials)BindingContext;
            
            App.Settings.RestUrl = settings.RestUrl;
            App.Settings.Username = settings.Username;
            App.Settings.Password = settings.Password;
            App.ItemManager.configHttpClient();

            string documentsPath = System.Environment.GetFolderPath(System.Environment.SpecialFolder.Personal);
            var filePath = Path.Combine(documentsPath, "settings.txt");
            if(File.Exists(filePath))
            {
                string[] credentialList = { App.Settings.RestUrl, App.Settings.Username, App.Settings.Password };
                File.WriteAllLines(filePath, credentialList);
                
            }
            else
            {
                System.IO.File.Create(filePath);
            }

            await Navigation.PopAsync();
        }

        async void LoadCredentials(object sender, EventArgs e)
        {
            var fellowItem = (FellowItem)BindingContext;
            await App.ItemManager.EditFellowAsync(fellowItem);

            string documentsPath = System.Environment.GetFolderPath(System.Environment.SpecialFolder.Personal);
            var filePath = System.IO.Path.Combine(documentsPath, "settings.txt");
            if (System.IO.File.Exists(filePath))
            {
                string[] credentialList = System.IO.File.ReadAllLines(filePath);
                App.Settings.RestUrl = credentialList[0];
                App.Settings.Username = credentialList[1];
                App.Settings.Password = credentialList[2];
            }

            await Navigation.PopAsync();
        }

    }
}