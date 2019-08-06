using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Text;
using System.Threading.Tasks;
using Newtonsoft.Json;

namespace SkatFellows
{
    public class RestService : IRestService
    {
        HttpClient client;
        String authData;
        String authHeaderValue;

        public List<FellowItem> Fellows { get; set; }
        public List<GameItem> Games { get; private set; }

        public RestService()
        {
            configHttpClient();
        }

        public void configHttpClient()
        {
            authData = string.Format("{0}:{1}", App.Settings.Username, App.Settings.Password);
            authHeaderValue = Convert.ToBase64String(Encoding.UTF8.GetBytes(authData));

            client = new HttpClient();
            client.MaxResponseContentBufferSize = 256000;
            client.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Basic", authHeaderValue);
        }

        public async Task<List<FellowItem>> RefreshFellowsAsync()
        {
            Fellows = new List<FellowItem>();

            var uri = new Uri(string.Format(App.Settings.RestUrl + "?cmd=getF", string.Empty));

            try
            {
                var response = await client.GetAsync(uri);
                if (response.IsSuccessStatusCode)
                {
                    var content = await response.Content.ReadAsStringAsync();
                    Fellows = JsonConvert.DeserializeObject<List<FellowItem>>(content);
                }
            }
            catch (Exception ex)
            {
                Debug.WriteLine(@"				ERROR {0}", ex.Message);
            }

            return Fellows;
        }

        public async Task AddFellowAsync(FellowItem item)
        {
            var uri = new Uri(string.Format(App.Settings.RestUrl + "?cmd=addF", string.Empty));

            try
            {
                var json = JsonConvert.SerializeObject(item);
                var content = new StringContent(json, Encoding.UTF8, "application/json");

                HttpResponseMessage response = null;
                
                response = await client.PostAsync(uri, content);
                if (response.IsSuccessStatusCode)
                {
                    var respContent = await response.Content.ReadAsStringAsync();
                    var respItem = JsonConvert.DeserializeObject<FellowItem>(respContent);
                    item.ID = respItem.ID;
                    Fellows.Add(item);
                }
                
                if (response.IsSuccessStatusCode)
                {
                    Debug.WriteLine(@"				Fellow successfully added.");
                }

            }
            catch (Exception ex)
            {
                Debug.WriteLine(@"				ERROR {0}", ex.Message);
            }
        }

        public async Task EditFellowAsync(FellowItem item)
        {
            var uri = new Uri(string.Format(App.Settings.RestUrl + "?cmd=chaF", string.Empty));

            try
            {
                var json = JsonConvert.SerializeObject(item);
                var content = new StringContent(json, Encoding.UTF8, "application/json");

                HttpResponseMessage response = null;

                response = await client.PostAsync(uri, content);
                if (response.IsSuccessStatusCode)
                {
                    var respContent = await response.Content.ReadAsStringAsync();
                    var respItem = JsonConvert.DeserializeObject<FellowItem>(respContent);
                    item.ID = respItem.ID;
                    Fellows.Add(item);
                }

                if (response.IsSuccessStatusCode)
                {
                    Debug.WriteLine(@"				Fellow successfully added.");
                }

            }
            catch (Exception ex)
            {
                Debug.WriteLine(@"				ERROR {0}", ex.Message);
            }
        }

        public async Task DeleteFellowAsync(FellowItem item)
        {
            var uri = new Uri(string.Format(App.Settings.RestUrl + "?cmd=remF", string.Empty));

            try
            {
                var json = JsonConvert.SerializeObject(item);
                var content = new StringContent(json, Encoding.UTF8, "application/json");

                var response = await client.PostAsync(uri, content);
                if (response.IsSuccessStatusCode)
                {
                    Debug.WriteLine(@"				Fellow successfully deleted.");
                }

            }
            catch (Exception ex)
            {
                Debug.WriteLine(@"				ERROR {0}", ex.Message);
            }
        }

        public async Task<List<GameItem>> RefreshGamesAsync()
        {
            Games = new List<GameItem>();

            var uri = new Uri(string.Format(App.Settings.RestUrl + "?cmd=getG", string.Empty));

            try
            {
                var response = await client.GetAsync(uri);
                if (response.IsSuccessStatusCode)
                {
                    var content = await response.Content.ReadAsStringAsync();
                    Games = JsonConvert.DeserializeObject<List<GameItem>>(content);
                }
            }
            catch (Exception ex)
            {
                Debug.WriteLine(@"				ERROR {0}", ex.Message);
            }

            return Games;

        }

        public async Task<List<GameItem>> GetGamesOfFellowAsync(FellowItem item)
        {
            Games = new List<GameItem>();

            var uri = new Uri(string.Format(App.Settings.RestUrl + "?cmd=getGoF", string.Empty));

            try
            {
                var json = JsonConvert.SerializeObject(item);
                var content = new StringContent(json, Encoding.UTF8, "application/json");

                var response = await client.PostAsync(uri, content);
                if (response.IsSuccessStatusCode)
                {
                    var responsecontent = await response.Content.ReadAsStringAsync();
                    Games = JsonConvert.DeserializeObject<List<GameItem>>(responsecontent);
                }
            }
            catch (Exception ex)
            {
                Debug.WriteLine(@"				ERROR {0}", ex.Message);
            }

            return Games;

        }

        public async Task AddGameAsync(GameItem item)
        {
            var uri = new Uri(string.Format(App.Settings.RestUrl + "?cmd=addG", string.Empty));

            try
            {
                var json = JsonConvert.SerializeObject(item);
                var content = new StringContent(json, Encoding.UTF8, "application/json");

                HttpResponseMessage response = null;

                response = await client.PostAsync(uri, content);
                if (response.IsSuccessStatusCode)
                {
                    var respContent = await response.Content.ReadAsStringAsync();
                    var respItem = JsonConvert.DeserializeObject<GameItem>(respContent);
                    item.ID = respItem.ID;
                    Games.Add(item);
                }

                if (response.IsSuccessStatusCode)
                {
                    Debug.WriteLine(@"				Fellow successfully added.");
                }

            }
            catch (Exception ex)
            {
                Debug.WriteLine(@"				ERROR {0}", ex.Message);
            }
        }

        public async Task DeleteGameAsync(GameItem item)
        {
            var uri = new Uri(string.Format(App.Settings.RestUrl + "?cmd=remG", string.Empty));

            try
            {
                var json = JsonConvert.SerializeObject(item);
                var content = new StringContent(json, Encoding.UTF8, "application/json");

                var response = await client.PostAsync(uri, content);
                if (response.IsSuccessStatusCode)
                {
                    Debug.WriteLine(@"				Fellow successfully deleted.");
                }

            }
            catch (Exception ex)
            {
                Debug.WriteLine(@"				ERROR {0}", ex.Message);
            }
        }

        public async Task<List<FellowItem>> GetScoringAsync()
        {
            Fellows = new List<FellowItem>();

            var uri = new Uri(string.Format(App.Settings.RestUrl + "?cmd=getST", string.Empty));

            try
            {
                var response = await client.GetAsync(uri);
                if (response.IsSuccessStatusCode)
                {
                    var content = await response.Content.ReadAsStringAsync();
                    Fellows = JsonConvert.DeserializeObject<List<FellowItem>>(content);
                    foreach( FellowItem Fellow in Fellows)
                    {
                        if (Fellow.Games > 0)
                        {
                            Fellow.Median = Fellow.Score / Fellow.Games;
                        }
                    }
                    Fellows.RemoveAll(x => x.Games == 0);
                    Fellows.Sort();
                }
            }
            catch (Exception ex)
            {
                Debug.WriteLine(@"				ERROR {0}", ex.Message);
            }

            return Fellows;
        }

        public async Task<List<FellowItem>> GetAllScoringAsync()
        {
            Fellows = new List<FellowItem>();

            var uri = new Uri(string.Format(App.Settings.RestUrl + "?cmd=getAST", string.Empty));

            try
            {
                var response = await client.GetAsync(uri);
                if (response.IsSuccessStatusCode)
                {
                    var content = await response.Content.ReadAsStringAsync();
                    Fellows = JsonConvert.DeserializeObject<List<FellowItem>>(content);
                    foreach (FellowItem Fellow in Fellows)
                    {
                        if (Fellow.Games > 0)
                        {
                            Fellow.Median = Fellow.Score / Fellow.Games;
                        }
                    }
                    Fellows.RemoveAll(x => x.Games == 0);
                    Fellows.Sort();
                }
            }
            catch (Exception ex)
            {
                Debug.WriteLine(@"				ERROR {0}", ex.Message);
            }

            return Fellows;
        }
    }
}
