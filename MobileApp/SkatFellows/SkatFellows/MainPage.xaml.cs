using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace SkatFellows
{
	public partial class MainPage : ContentPage
	{
		public MainPage()
		{
			InitializeComponent();
		}

        private void OnFellowsClicked(object sender, EventArgs e)
        {
            var fellowsPage = new FellowsListPage();
            Navigation.PushAsync( fellowsPage );
        }

        private void OnGamesClicked(object sender, EventArgs e)
        {
            var gameItem = new GameItem();
            var gamesPage = new GamePage();
            gamesPage.BindingContext = gameItem;
            Navigation.PushAsync(gamesPage);
        }

        private void OnOverallClicked(object sender, EventArgs e)
        {
            var scoringPage = new TabbedScorePage();
            scoringPage.BindingContext = App.ItemManager.Fellows;
            Navigation.PushAsync(scoringPage);
        }

        private void OnSettingsClicked(object sender, EventArgs e)
        {
            var settingsPage = new CredentialPage();
            settingsPage.BindingContext = App.Settings;
            Navigation.PushAsync(settingsPage);
        }
    }

}
