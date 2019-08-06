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
	public partial class EditGamePage : ContentPage
	{
		public EditGamePage ()
		{
			InitializeComponent ();
		}

        async void EditGame(object sender, EventArgs e)
        {
            var gameItem = (GameItem)BindingContext;
            //await App.ItemManager.EditGameAsync(fellowItem);
            await Navigation.PopAsync();
        }

        async void DeleteGame(object sender, EventArgs e)
        {
            var answer = await DisplayAlert("Delete?", "Do you really want to delete this Fellow?", "Yes", "No");
            if (answer)
            {
                var gameItem = (GameItem)BindingContext;
                await App.ItemManager.DeleteGameAsync(gameItem);
                await Navigation.PopAsync();
            }
        }
    }
}