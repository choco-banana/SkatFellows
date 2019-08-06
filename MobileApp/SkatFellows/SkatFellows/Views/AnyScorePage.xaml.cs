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
	public partial class AnyScorePage : ContentPage
	{

		public AnyScorePage ()
		{
			InitializeComponent ();
		}

        protected async override void OnAppearing()
        {
            base.OnAppearing();

            listView.ItemsSource = await App.ItemManager.GetAllScoringAsync();
        }

        private void OnItemSelected(object sender, EventArgs e)
        {
        }
    }
}