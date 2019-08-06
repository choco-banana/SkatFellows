using System;
using Xamarin.Forms;

namespace SkatFellows
{
    //[XamlCompilation(XamlCompilationOptions.Compile)]
    public partial class GamePage : ContentPage
    {
        public GamePage()
        {
            InitializeComponent();

        }

        protected async override void OnAppearing()
        {
            base.OnAppearing();
            playerSelect.ItemsSource = await App.ItemManager.RefreshFellowsAsync();
            //playerSelect.ItemDisplayBinding = new Binding("Name");
        }

        async void AddNewGame(object sender, EventArgs e)
        {
            var gameItem = (GameItem)BindingContext;
            if (playerSelect.SelectedItem != null)
            {
                gameItem.PlayerID = ((FellowItem)playerSelect.SelectedItem).ID;
                await App.ItemManager.AddGameAsync(gameItem);
                await Navigation.PopAsync();
            }
            else
            {
                await DisplayAlert("Alert", "Select a Player", "OK");
            }
            
        }
    }
}