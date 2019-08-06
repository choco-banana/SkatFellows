using System;
using Xamarin.Forms;

namespace SkatFellows
{
	public partial class NewFellowPage : ContentPage
	{
		public NewFellowPage ()
		{
			InitializeComponent ();
		}

        async void AddNewFellow(object sender, EventArgs e)
        {
            var fellowItem = (FellowItem)BindingContext;
            await App.ItemManager.AddFellowAsync(fellowItem);
            await Navigation.PopAsync();
        }
    }
}