using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;

namespace SkatFellows
{
    interface IRestService
    {
        Task<List<FellowItem>> RefreshFellowsAsync();

        Task AddFellowAsync(FellowItem item);

        Task DeleteFellowAsync(FellowItem item);

        Task<List<GameItem>> RefreshGamesAsync();

        Task<List<GameItem>> GetGamesOfFellowAsync(FellowItem item);

        Task AddGameAsync(GameItem item);

        Task DeleteGameAsync(GameItem item);
    }
}
