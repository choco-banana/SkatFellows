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
	public partial class CurrentScorePage : ContentPage
	{
		public CurrentScorePage()
		{
			InitializeComponent ();

            startDatePicker.MinimumDate = new DateTime(2017,01,01);
            startDatePicker.Date = DateTime.Now;
        }

        protected async override void OnAppearing()
        {
            base.OnAppearing();

            listView.ItemsSource = await App.ItemManager.GetScoringAsync();
        }

        private void OnItemSelected(object sender, EventArgs e)
        {

        }

        private void OnDateSelected(object sender, EventArgs e)
        {

        }
    }
}