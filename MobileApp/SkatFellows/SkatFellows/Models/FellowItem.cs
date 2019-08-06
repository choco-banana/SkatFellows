using System;
using System.Collections.Generic;
using System.Text;

namespace SkatFellows
{
    public class FellowItem : IComparable<FellowItem>
    {
        public int ID { get; set; }

        public string Name { get; set; }

        public int Score { get; set; }

        public int Games { get; set; }

        public int Median { get; set; }

        public int CompareTo(FellowItem other)
        {
            // Compares Score.
            if (this.Score > other.Score)
            {
                return -1;
            }
            else if(this.Score < other.Score)
            {
                return 1;
            }
            else
            {
                return 0;
            }
        }
    }
}
