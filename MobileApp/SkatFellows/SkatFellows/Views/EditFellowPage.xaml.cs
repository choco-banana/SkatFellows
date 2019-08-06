using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace SkatFellows
{
	[XamlCompilation(XamlCompilationOptions.Compile)]
	public partial class EditFellowPage : ContentPage
	{
        List<GameItem> FellowsGames;

        public EditFellowPage ()
		{
			InitializeComponent ();
		}

        protected async override void OnAppearing()
        {
            base.OnAppearing();
            var fellowItem = (FellowItem)BindingContext;
            FellowsGames = await App.ItemManager.GetGamesOfFellowAsync(fellowItem);
            listView.ItemsSource = FellowsGames;
        }

        async void EditFellow(object sender, EventArgs e)
        {
            var fellowItem = (FellowItem)BindingContext;
            await App.ItemManager.EditFellowAsync(fellowItem);
            await Navigation.PopAsync();
        }

        async void DeleteFellow(object sender, EventArgs e)
        {
            var answer = await DisplayAlert("Delete?", "Do you really want to delete this Fellow?", "Yes", "No");
            if (answer)
            {
                var fellowItem = (FellowItem)BindingContext;
                await App.ItemManager.DeleteFellowAsync(fellowItem);
                await Navigation.PopAsync();
            }
        }

        private void OnItemSelected()
        {
            var gameItem = ((GameItem)listView.SelectedItem);
            var editGamePage = new EditGamePage();
            editGamePage.BindingContext = gameItem;
            Navigation.PushAsync(editGamePage);
        }

    }
}