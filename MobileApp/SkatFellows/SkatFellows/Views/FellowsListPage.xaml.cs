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
	public partial class FellowsListPage : ContentPage
	{
        List<FellowItem> Fellows;

		public FellowsListPage ()
		{
			InitializeComponent ();
		}

        protected async override void OnAppearing()
        {
            base.OnAppearing();
            Fellows = await App.ItemManager.RefreshFellowsAsync();
            listView.ItemsSource = Fellows;
        }

        private void OnAddItemClicked()
        {
            var fellowItem = new FellowItem();
            var addFellowPage = new NewFellowPage();
            addFellowPage.BindingContext = fellowItem;
            Navigation.PushAsync(addFellowPage);
        }

        private void OnItemSelected()
        {
            var fellowItem = ((FellowItem)listView.SelectedItem);
            var editFellowPage = new EditFellowPage();
            editFellowPage.BindingContext = fellowItem;
            Navigation.PushAsync(editFellowPage);
        }
	}
}