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
    public partial class TabbedScorePage : TabbedPage
    {
        public TabbedScorePage()
        {
            InitializeComponent();

            Children.Add(new CurrentScorePage());
            Children.Add(new AnyScorePage());

        }
    }
}