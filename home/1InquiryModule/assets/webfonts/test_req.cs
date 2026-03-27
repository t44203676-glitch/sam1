using System;
using System.Net.Http;
using System.Threading.Tasks;

class Program {
    static async Task Main() {
        using (var client = new HttpClient()) {
            client.DefaultRequestHeaders.Add("User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36");
            var res = await client.GetAsync("https://fonts.cdnfonts.com/css/ge-ss-two");
            Console.WriteLine(res.StatusCode);
            if (res.IsSuccessStatusCode) {
                var content = await res.Content.ReadAsStringAsync();
                System.IO.File.WriteAllText("test.css", content);
                Console.WriteLine("Success!");
            }
        }
    }
}
