using System;
using System.Collections.Generic;
using Xamarin.Forms;
using Xamarin.Forms.Xaml;

[assembly: XamlCompilation (XamlCompilationOptions.Compile)]
namespace SkatFellows
{
	public partial class App : Application
	{
        public static Credentials Settings;
        public static RestService ItemManager;

        public App ()
		{
            Settings = new Credentials();
            ItemManager = new RestService();

            InitializeComponent();

            MainPage = new NavigationPage( new MainPage() );
		}

		protected override void OnStart ()
		{
			// Handle when your app starts
		}

		protected override void OnSleep ()
		{
			// Handle when your app sleeps
		}

		protected override void OnResume ()
		{
			// Handle when your app resumes
		}
	}
}
