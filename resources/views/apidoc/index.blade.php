<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>API Reference</title>

    <link rel="stylesheet" href="/docs/css/style.css" />
    <script src="/docs/js/all.js"></script>


          <script>
        $(function() {
            setupLanguages(["bash","javascript"]);
        });
      </script>
      </head>

  <body class="">
    <a href="#" id="nav-button">
      <span>
        NAV
        <img src="/docs/images/navbar.png" />
      </span>
    </a>
    <div class="tocify-wrapper">
        <img src="/docs/images/logo.png" />
                    <div class="lang-selector">
                                  <a href="#" data-language-name="bash">bash</a>
                                  <a href="#" data-language-name="javascript">javascript</a>
                            </div>
                            <div class="search">
              <input type="text" class="search" id="input-search" placeholder="Search">
            </div>
            <ul class="search-results"></ul>
              <div id="toc">
      </div>
                    <ul class="toc-footer">
                                  <li><a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a></li>
                            </ul>
            </div>
    <div class="page-wrapper">
      <div class="dark-box"></div>
      <div class="content">
          <!-- START_INFO -->
<h1>Info</h1>
<p>Welcome to the generated API reference.
<a href="{{ route("apidoc", ["format" => ".json"]) }}">Get Postman Collection</a></p>
<!-- END_INFO -->
<h1>general</h1>
<!-- START_0c068b4037fb2e47e71bd44bd36e3e2a -->
<h2>Authorize a client to access the user&#039;s account.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET oauth/authorize</code></p>
<!-- END_0c068b4037fb2e47e71bd44bd36e3e2a -->
<!-- START_e48cc6a0b45dd21b7076ab2c03908687 -->
<h2>Approve the authorization request.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST oauth/authorize</code></p>
<!-- END_e48cc6a0b45dd21b7076ab2c03908687 -->
<!-- START_de5d7581ef1275fce2a229b6b6eaad9c -->
<h2>Deny the authorization request.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE oauth/authorize</code></p>
<!-- END_de5d7581ef1275fce2a229b6b6eaad9c -->
<!-- START_a09d20357336aa979ecd8e3972ac9168 -->
<h2>Authorize a client to access the user&#039;s account.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/oauth/token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST oauth/token</code></p>
<!-- END_a09d20357336aa979ecd8e3972ac9168 -->
<!-- START_d6a56149547e03307199e39e03e12d1c -->
<h2>Get all of the authorized tokens for the authenticated user.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/oauth/tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET oauth/tokens</code></p>
<!-- END_d6a56149547e03307199e39e03e12d1c -->
<!-- START_a9a802c25737cca5324125e5f60b72a5 -->
<h2>Delete the given token.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/oauth/tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/tokens/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE oauth/tokens/{token_id}</code></p>
<!-- END_a9a802c25737cca5324125e5f60b72a5 -->
<!-- START_abe905e69f5d002aa7d26f433676d623 -->
<h2>Get a fresh transient token cookie for the authenticated user.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/oauth/token/refresh" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/token/refresh"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST oauth/token/refresh</code></p>
<!-- END_abe905e69f5d002aa7d26f433676d623 -->
<!-- START_babcfe12d87b8708f5985e9d39ba8f2c -->
<h2>Get all of the clients for the authenticated user.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/clients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET oauth/clients</code></p>
<!-- END_babcfe12d87b8708f5985e9d39ba8f2c -->
<!-- START_9eabf8d6e4ab449c24c503fcb42fba82 -->
<h2>Store a new client.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/clients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST oauth/clients</code></p>
<!-- END_9eabf8d6e4ab449c24c503fcb42fba82 -->
<!-- START_784aec390a455073fc7464335c1defa1 -->
<h2>Update the given client.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/clients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT oauth/clients/{client_id}</code></p>
<!-- END_784aec390a455073fc7464335c1defa1 -->
<!-- START_1f65a511dd86ba0541d7ba13ca57e364 -->
<h2>Delete the given client.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/clients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE oauth/clients/{client_id}</code></p>
<!-- END_1f65a511dd86ba0541d7ba13ca57e364 -->
<!-- START_9e281bd3a1eb1d9eb63190c8effb607c -->
<h2>Get all of the available scopes for the application.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/oauth/scopes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/scopes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET oauth/scopes</code></p>
<!-- END_9e281bd3a1eb1d9eb63190c8effb607c -->
<!-- START_9b2a7699ce6214a79e0fd8107f8b1c9e -->
<h2>Get all of the personal access tokens for the authenticated user.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/personal-access-tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET oauth/personal-access-tokens</code></p>
<!-- END_9b2a7699ce6214a79e0fd8107f8b1c9e -->
<!-- START_a8dd9c0a5583742e671711f9bb3ee406 -->
<h2>Create a new personal access token for the user.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/personal-access-tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST oauth/personal-access-tokens</code></p>
<!-- END_a8dd9c0a5583742e671711f9bb3ee406 -->
<!-- START_bae65df80fd9d72a01439241a9ea20d0 -->
<h2>Delete the given token.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/oauth/personal-access-tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/oauth/personal-access-tokens/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE oauth/personal-access-tokens/{token_id}</code></p>
<!-- END_bae65df80fd9d72a01439241a9ea20d0 -->
<!-- START_7cdc969720b1845c0d6943ec069f0c81 -->
<h2>api/campaigns/reward</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/campaigns/reward" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/campaigns/reward"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/campaigns/reward</code></p>
<!-- END_7cdc969720b1845c0d6943ec069f0c81 -->
<!-- START_a0d25fd123876e0d547119af2593d887 -->
<h2>api/campaigns/push_reward</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/campaigns/push_reward" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/campaigns/push_reward"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/campaigns/push_reward</code></p>
<!-- END_a0d25fd123876e0d547119af2593d887 -->
<!-- START_a1655dd1f8e74e26bfab15dcf93539d4 -->
<h2>api/campaigns/{campaign_id}/state</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/campaigns/1/state" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/campaigns/1/state"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/campaigns/{campaign_id}/state</code></p>
<!-- END_a1655dd1f8e74e26bfab15dcf93539d4 -->
<!-- START_12e37982cc5398c7100e59625ebb5514 -->
<h2>api/users</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/users</code></p>
<!-- END_12e37982cc5398c7100e59625ebb5514 -->
<!-- START_5e07a013cf7b67ab90fb85055c459127 -->
<h2>api/users/V2/{user_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/V2/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/V2/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/V2/{user_id}</code></p>
<!-- END_5e07a013cf7b67ab90fb85055c459127 -->
<!-- START_257e1542084739741a0f53c8115eea07 -->
<h2>api/users/{user_id}/ban_boards</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/1/ban_boards" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/ban_boards"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/{user_id}/ban_boards</code></p>
<!-- END_257e1542084739741a0f53c8115eea07 -->
<!-- START_92591341987ecd222cb0d08f36c129a0 -->
<h2>api/users/{user_id}/withdraw</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/users/1/withdraw" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/withdraw"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/users/{user_id}/withdraw</code></p>
<!-- END_92591341987ecd222cb0d08f36c129a0 -->
<!-- START_3ed66919853dc32d9d2f344ad5687e78 -->
<h2>api/users/{user_id}/push</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/1/push" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/push"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/{user_id}/push</code></p>
<!-- END_3ed66919853dc32d9d2f344ad5687e78 -->
<!-- START_36325349a9a746bca8ec2d36b3bdd850 -->
<h2>api/users/{user_id}/push</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/users/1/push" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/push"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/users/{user_id}/push</code></p>
<!-- END_36325349a9a746bca8ec2d36b3bdd850 -->
<!-- START_aed2403ea64dddcd0ff613c802e5fec5 -->
<h2>api/users/{user_id}/password</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/users/1/password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/users/{user_id}/password</code></p>
<!-- END_aed2403ea64dddcd0ff613c802e5fec5 -->
<!-- START_425c13d07b5765c09b588bb4514c81fa -->
<h2>api/users/{user_id}/nickname</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/users/1/nickname" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/nickname"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/users/{user_id}/nickname</code></p>
<!-- END_425c13d07b5765c09b588bb4514c81fa -->
<!-- START_d2f9c3524ecc745f22288f87b5155973 -->
<h2>api/users/{user_id}/additional_info</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/users/1/additional_info" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/additional_info"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/users/{user_id}/additional_info</code></p>
<!-- END_d2f9c3524ecc745f22288f87b5155973 -->
<!-- START_7546805059c1ff4c5a6d063d735364ce -->
<h2>api/users/{user_id}/gender</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/users/1/gender" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/gender"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/users/{user_id}/gender</code></p>
<!-- END_7546805059c1ff4c5a6d063d735364ce -->
<!-- START_b5e380f5e3e08de9d340daf335b1a5a9 -->
<h2>api/users/{user_id}/point_info</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/1/point_info" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/point_info"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/{user_id}/point_info</code></p>
<!-- END_b5e380f5e3e08de9d340daf335b1a5a9 -->
<!-- START_c56017c9bc338319f708922925fc3f42 -->
<h2>api/users/{user_id}/point_ranking</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/1/point_ranking" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/point_ranking"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/{user_id}/point_ranking</code></p>
<!-- END_c56017c9bc338319f708922925fc3f42 -->
<!-- START_da713d8903ca68174b77901a1d42ccd1 -->
<h2>api/users/{user_id}/point_ranking_graph</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/1/point_ranking_graph" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/point_ranking_graph"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/{user_id}/point_ranking_graph</code></p>
<!-- END_da713d8903ca68174b77901a1d42ccd1 -->
<!-- START_f0305d9532bbeea8bbd277e97ced8f61 -->
<h2>api/users/{user_id}/point_log</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/1/point_log" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/point_log"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/{user_id}/point_log</code></p>
<!-- END_f0305d9532bbeea8bbd277e97ced8f61 -->
<!-- START_fd215db1685484966413a24bba593c82 -->
<h2>api/users/{user_id}/boards</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/1/boards" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/boards"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/{user_id}/boards</code></p>
<!-- END_fd215db1685484966413a24bba593c82 -->
<!-- START_e8d2c35c9b4a4687476713d87ca39701 -->
<h2>api/users/{user_id}/activity_log</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/1/activity_log" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/activity_log"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/{user_id}/activity_log</code></p>
<!-- END_e8d2c35c9b4a4687476713d87ca39701 -->
<!-- START_dc6257b6803d4f2c03328f2ab40e08a0 -->
<h2>api/users/{user_id}/setting</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/1/setting" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/setting"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Unauthenticated."
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/{user_id}/setting</code></p>
<!-- END_dc6257b6803d4f2c03328f2ab40e08a0 -->
<!-- START_04c1b489ed0af96a8b49c07fa8c047c8 -->
<h2>api/users/bulk/ranking_v5</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/bulk/ranking_v5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/bulk/ranking_v5"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/bulk/ranking_v5</code></p>
<!-- END_04c1b489ed0af96a8b49c07fa8c047c8 -->
<!-- START_1f95ca04f18fc10ba2e1c07515306943 -->
<h2>api/users/check/email</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/users/check/email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/check/email"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/users/check/email</code></p>
<!-- END_1f95ca04f18fc10ba2e1c07515306943 -->
<!-- START_c5773a64f8b4d2df84301b953b4caeb8 -->
<h2>api/users/check/nickname</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/users/check/nickname" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/check/nickname"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/users/check/nickname</code></p>
<!-- END_c5773a64f8b4d2df84301b953b4caeb8 -->
<!-- START_173d87247c39ef292bffe80c7e880596 -->
<h2>api/users/check/V2/nickname</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/users/check/V2/nickname" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/check/V2/nickname"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/users/check/V2/nickname</code></p>
<!-- END_173d87247c39ef292bffe80c7e880596 -->
<!-- START_c527dcd7f3e7400067a0c62602aeaf10 -->
<h2>api/users</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/users</code></p>
<!-- END_c527dcd7f3e7400067a0c62602aeaf10 -->
<!-- START_321cdbbb39234fedeaf5344a6cb416b6 -->
<h2>api/users/V2</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/users/V2" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/V2"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/users/V2</code></p>
<!-- END_321cdbbb39234fedeaf5344a6cb416b6 -->
<!-- START_fae47008158ac53f383fa2eff70bf9ff -->
<h2>api/users/V3</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/users/V3" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/V3"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/users/V3</code></p>
<!-- END_fae47008158ac53f383fa2eff70bf9ff -->
<!-- START_7f0e447064b79974907cfda3dc50112e -->
<h2>api/users/find_validate_mobile</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/users/find_validate_mobile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/find_validate_mobile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/users/find_validate_mobile</code></p>
<!-- END_7f0e447064b79974907cfda3dc50112e -->
<!-- START_fc8f6fcdc6e7946059ba394b924736b1 -->
<h2>api/users/validate_mobile</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/users/validate_mobile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/validate_mobile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/users/validate_mobile</code></p>
<!-- END_fc8f6fcdc6e7946059ba394b924736b1 -->
<!-- START_96f8d0446eca9fa46def3f52769fcb0e -->
<h2>api/users/validate_sms_number</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/users/validate_sms_number" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/validate_sms_number"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/users/validate_sms_number</code></p>
<!-- END_96f8d0446eca9fa46def3f52769fcb0e -->
<!-- START_19f5af28fb97f08b3b720587b1322e77 -->
<h2>api/users/find/account</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/users/find/account" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/find/account"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (422):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "The given data was invalid.",
    "errors": {
        "mobile": [
            "The mobile field is required."
        ]
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/users/find/account</code></p>
<!-- END_19f5af28fb97f08b3b720587b1322e77 -->
<!-- START_d12e105ff4c0774cda2036c6e3db9dac -->
<h2>api/users/find/reset_password</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/users/find/reset_password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/find/reset_password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/users/find/reset_password</code></p>
<!-- END_d12e105ff4c0774cda2036c6e3db9dac -->
<!-- START_2b77a9c668c94b3caefdf93f900e374f -->
<h2>api/users/reset_mobile</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/users/reset_mobile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/reset_mobile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/users/reset_mobile</code></p>
<!-- END_2b77a9c668c94b3caefdf93f900e374f -->
<!-- START_012c4f8636c43ddd472b56bde73cee56 -->
<h2>api/users/{user_id}/profile_photo</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/users/1/profile_photo" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/users/1/profile_photo"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/users/{user_id}/profile_photo</code></p>
<!-- END_012c4f8636c43ddd472b56bde73cee56 -->
<!-- START_4099360e72d69df6771eca278334cffa -->
<h2>api/auth/V3/login</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/auth/V3/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/auth/V3/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/auth/V3/login</code></p>
<!-- END_4099360e72d69df6771eca278334cffa -->
<!-- START_ecd9bef3e1ef4ac83efdc5e338f02f79 -->
<h2>api/auth/V3/social_login</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/auth/V3/social_login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/auth/V3/social_login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/auth/V3/social_login</code></p>
<!-- END_ecd9bef3e1ef4ac83efdc5e338f02f79 -->
<!-- START_19ff1b6f8ce19d3c444e9b518e8f7160 -->
<h2>api/auth/logout</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/auth/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/auth/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/auth/logout</code></p>
<!-- END_19ff1b6f8ce19d3c444e9b518e8f7160 -->
<!-- START_9fd414d76d562f4c44e108b6a1d42da6 -->
<h2>api/boards/info/{board_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/boards/info/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/info/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/boards/info/{board_id}</code></p>
<!-- END_9fd414d76d562f4c44e108b6a1d42da6 -->
<!-- START_59385f78f77e56d024e98d79f5985975 -->
<h2>api/boards/fanfeed_best</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/boards/fanfeed_best" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/fanfeed_best"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/boards/fanfeed_best</code></p>
<!-- END_59385f78f77e56d024e98d79f5985975 -->
<!-- START_b99ebe04730beea561c4c6435fcdfcac -->
<h2>api/boards/artist_best</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/boards/artist_best" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/artist_best"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/boards/artist_best</code></p>
<!-- END_b99ebe04730beea561c4c6435fcdfcac -->
<!-- START_96a82cfb2a4418595aa79cee1e5cb1d2 -->
<h2>api/boards/V6/mix</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/boards/V6/mix" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/V6/mix"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/boards/V6/mix</code></p>
<!-- END_96a82cfb2a4418595aa79cee1e5cb1d2 -->
<!-- START_37583d72ae0463b50b66893cbd896be7 -->
<h2>api/boards/V6/{type}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/boards/V6/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/V6/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/boards/V6/{type}</code></p>
<!-- END_37583d72ae0463b50b66893cbd896be7 -->
<!-- START_526abac8d394d5cbc983cf2df12d2684 -->
<h2>api/boards/like</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/boards/like" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/like"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/boards/like</code></p>
<!-- END_526abac8d394d5cbc983cf2df12d2684 -->
<!-- START_a926b780b49f655d18fc1dd9f8a7763f -->
<h2>api/boards/ban</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/boards/ban" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/ban"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/boards/ban</code></p>
<!-- END_a926b780b49f655d18fc1dd9f8a7763f -->
<!-- START_29d5004554880779a00f30c29deae96d -->
<h2>api/boards/bulk/youtube_api_key</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/boards/bulk/youtube_api_key" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/bulk/youtube_api_key"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/boards/bulk/youtube_api_key</code></p>
<!-- END_29d5004554880779a00f30c29deae96d -->
<!-- START_ba19e3ffa0fe3810587c25e78bccaefe -->
<h2>api/boards/fanfeed</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/boards/fanfeed" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/fanfeed"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/boards/fanfeed</code></p>
<!-- END_ba19e3ffa0fe3810587c25e78bccaefe -->
<!-- START_bab12383fb251f9dd4c86e40d800118e -->
<h2>api/boards/item</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/boards/item" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/item"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/boards/item</code></p>
<!-- END_bab12383fb251f9dd4c86e40d800118e -->
<!-- START_b8c1ff89a8bb30bfaa0d25aa1639ad10 -->
<h2>api/boards/refresh/{board_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/boards/refresh/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/refresh/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {
        "is_like": 0,
        "user_item_count": 0,
        "total_like_count": 0,
        "total_item_count": 0,
        "comment_count": 0
    },
    "resultCode": {
        "code": 0,
        "message": "Success"
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/boards/refresh/{board_id}</code></p>
<!-- END_b8c1ff89a8bb30bfaa0d25aa1639ad10 -->
<!-- START_f4fd1d1f81ccd2d6e03a81ae7365f58f -->
<h2>api/boards/refresh/V2/{board_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/boards/refresh/V2/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/boards/refresh/V2/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/boards/refresh/V2/{board_id}</code></p>
<!-- END_f4fd1d1f81ccd2d6e03a81ae7365f58f -->
<!-- START_ad3318b1c6daac0d851e629eebab6ff5 -->
<h2>api/comments/{comment_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/comments/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/comments/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/comments/{comment_id}</code></p>
<!-- END_ad3318b1c6daac0d851e629eebab6ff5 -->
<!-- START_3ec437415ab7ebf106999ee80167084c -->
<h2>api/comments/V3</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/comments/V3" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/comments/V3"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (422):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "The given data was invalid.",
    "errors": {
        "board_id": [
            "The board id field is required."
        ],
        "app": [
            "The app field is required."
        ]
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/comments/V3</code></p>
<!-- END_3ec437415ab7ebf106999ee80167084c -->
<!-- START_454ffb87bcfa9b5afc4ae218a288fa27 -->
<h2>api/comments/V3/reply</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/comments/V3/reply" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/comments/V3/reply"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (422):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "The given data was invalid.",
    "errors": {
        "app": [
            "The app field is required."
        ],
        "parent_id": [
            "The parent id field is required."
        ]
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/comments/V3/reply</code></p>
<!-- END_454ffb87bcfa9b5afc4ae218a288fa27 -->
<!-- START_6c560cb463cae69ddba197afa896608b -->
<h2>api/comments</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/comments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/comments"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/comments</code></p>
<!-- END_6c560cb463cae69ddba197afa896608b -->
<!-- START_58757b0cd1f86ab24a134fafcb3c6c6b -->
<h2>api/comments/reply</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/comments/reply" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/comments/reply"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/comments/reply</code></p>
<!-- END_58757b0cd1f86ab24a134fafcb3c6c6b -->
<!-- START_3175b3d008d6dd32264bdacb799407b9 -->
<h2>api/comments/delete/{comment_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/comments/delete/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/comments/delete/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/comments/delete/{comment_id}</code></p>
<!-- END_3175b3d008d6dd32264bdacb799407b9 -->
<!-- START_5a67d8fe72a333af0bce52e235ac7776 -->
<h2>api/comments/like</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/comments/like" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/comments/like"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/comments/like</code></p>
<!-- END_5a67d8fe72a333af0bce52e235ac7776 -->
<!-- START_032916ef8eb3d604d6309672df4113fb -->
<h2>api/comments/report</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/comments/report" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/comments/report"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/comments/report</code></p>
<!-- END_032916ef8eb3d604d6309672df4113fb -->
<!-- START_93da5e0ad5b08e7f70d4050ac33627eb -->
<h2>api/notices</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/notices" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/notices"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (422):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "The given data was invalid.",
    "errors": {
        "app": [
            "The app field is required."
        ],
        "next": [
            "The next field is required."
        ]
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/notices</code></p>
<!-- END_93da5e0ad5b08e7f70d4050ac33627eb -->
<!-- START_e2dcf090e107d6275ef19ed508f38cb8 -->
<h2>api/schedules/V2</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/schedules/V2" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/schedules/V2"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/schedules/V2</code></p>
<!-- END_e2dcf090e107d6275ef19ed508f38cb8 -->
<!-- START_e8da3b2b6813a08a862944fc42e22e5b -->
<h2>api/schedules/check</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/schedules/check" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/schedules/check"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/schedules/check</code></p>
<!-- END_e8da3b2b6813a08a862944fc42e22e5b -->
<!-- START_400fb4764df1b01a1c10c5790a4e4f40 -->
<h2>api/banners</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/banners" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/banners"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/banners</code></p>
<!-- END_400fb4764df1b01a1c10c5790a4e4f40 -->
<!-- START_83ec4451d165999951e6f0de21a04a25 -->
<h2>api/musics/V2</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/musics/V2" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/musics/V2"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/musics/V2</code></p>
<!-- END_83ec4451d165999951e6f0de21a04a25 -->
<!-- START_d4b237769cd2fb2e4a814c2a8aa9ce93 -->
<h2>api/musics/V3</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/musics/V3" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/musics/V3"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/musics/V3</code></p>
<!-- END_d4b237769cd2fb2e4a814c2a8aa9ce93 -->
<!-- START_fe36999cb4a053dcb27ac2d7e4cc587b -->
<h2>api/musics/{music_id}/state</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/api/musics/1/state" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/musics/1/state"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/musics/{music_id}/state</code></p>
<!-- END_fe36999cb4a053dcb27ac2d7e4cc587b -->
<!-- START_5c09ccfabd694ac591f822f60ec8d3a9 -->
<h2>api/musics/{music_id}/reward</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/musics/1/reward" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/musics/1/reward"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/musics/{music_id}/reward</code></p>
<!-- END_5c09ccfabd694ac591f822f60ec8d3a9 -->
<!-- START_2fd4876e0cae97fc7a9ed03cce15c07b -->
<h2>api/musics/videos</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/musics/videos" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/musics/videos"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/musics/videos</code></p>
<!-- END_2fd4876e0cae97fc7a9ed03cce15c07b -->
<!-- START_e8fab99d5bd1eaf8db78593a2dbc3208 -->
<h2>api/musics/videos/V2</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/musics/videos/V2" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/musics/videos/V2"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/musics/videos/V2</code></p>
<!-- END_e8fab99d5bd1eaf8db78593a2dbc3208 -->
<!-- START_dff1230b34404242ec0da99eaee09e90 -->
<h2>api/albums</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/albums" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/albums"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/albums</code></p>
<!-- END_dff1230b34404242ec0da99eaee09e90 -->
<!-- START_53db78b933ec8e57b72904694c56304f -->
<h2>api/albums/V2</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/albums/V2" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/albums/V2"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/albums/V2</code></p>
<!-- END_53db78b933ec8e57b72904694c56304f -->
<!-- START_973d4b182a8aa51a75caf2936e585c31 -->
<h2>api/albums/musics</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/albums/musics" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/albums/musics"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/albums/musics</code></p>
<!-- END_973d4b182a8aa51a75caf2936e585c31 -->
<!-- START_957f459c48471c663d2ca34ff8bc9eea -->
<h2>api/albums/V2/musics</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/albums/V2/musics" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/albums/V2/musics"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/albums/V2/musics</code></p>
<!-- END_957f459c48471c663d2ca34ff8bc9eea -->
<!-- START_7f083b1da9a7c07a1ac561a6399d5828 -->
<h2>api/shopitems/banner</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/shopitems/banner" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/shopitems/banner"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {},
    "resultCode": {
        "code": -1001,
        "message": "The given data was invalid."
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/shopitems/banner</code></p>
<!-- END_7f083b1da9a7c07a1ac561a6399d5828 -->
<!-- START_ddc0cf784b6c26b1db4932e24f6a7978 -->
<h2>api/log/referrer</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/log/referrer" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/log/referrer"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/log/referrer</code></p>
<!-- END_ddc0cf784b6c26b1db4932e24f6a7978 -->
<!-- START_f7828fe70326ce6166fdba9c0c9d80ed -->
<h2>api/search</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/search" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/search"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {
        "more": {
            "board_more": true,
            "fan_feed_more": true,
            "music_more": true
        },
        "board_list": [
            {
                "id": 2701,
                "type": "instagram",
                "post": "\/p\/B4uJbjCjGjg\/",
                "title": "달콤하게 츄",
                "contents": "???? |CHU CRΣΔM ????| eng. O__pen!! .\r\n.\r\n▶️https:\/\/youtu.be\/XD7CESu7wLs\r\n.\r\n#chucream #eng",
                "thumbnail_url": "\/celeb\/images\/instagram\/thumbnail\/instagram_pB4uJbjCjGjg1573539314_0.jpg",
                "created_at": "4 days ago"
            },
            {
                "id": 2594,
                "type": "vlive",
                "post": "video\/159850?channelCode=E173B7",
                "title": null,
                "contents": "\n\n[MV] Nam Yun(남윤) _ I'll be a star (웹드라마 SPUNK OST - Part.1)\n\n",
                "thumbnail_url": "\/celeb\/images\/vlive\/thumbnail\/celeb_vlive_video1598501573463459_0.jpg",
                "created_at": "4 days ago"
            },
            {
                "id": 2595,
                "type": "vlive",
                "post": "video\/159994?channelCode=E173B7",
                "title": null,
                "contents": "\n\n너의 지금을 응원해_웹드라마 SPUNK(스펑크) EP13. '작은 용기'가 필요할 때\n\n",
                "thumbnail_url": "\/celeb\/images\/vlive\/thumbnail\/celeb_vlive_video1599941573463484_0.jpg",
                "created_at": "4 days ago"
            },
            {
                "id": 2590,
                "type": "vlive",
                "post": "video\/159332?channelCode=E173B7",
                "title": null,
                "contents": "\n\n버스킹 하기도 전에 끝? _웹드라마 SPUNK(스펑크) EP12. 드디어 그날이 왔다\n\n",
                "thumbnail_url": "\/celeb\/images\/vlive\/thumbnail\/celeb_vlive_video1593321573204259_0.jpg",
                "created_at": "1 week ago"
            }
        ],
        "fan_feed_list": [
            {
                "id": 2429,
                "type": "fanfeed",
                "post": null,
                "title": "123",
                "contents": "test",
                "thumbnail_url": "\/images\/fanfeed\/thumbnail\/krieshachu_fanfeed_1571208590_cropped",
                "created_at": "4 weeks ago"
            },
            {
                "id": 2374,
                "type": "fanfeed",
                "post": null,
                "title": "test8",
                "contents": "test8c",
                "thumbnail_url": "\/images\/fanfeed\/thumbnail\/krieshachu_fanfeed_1571039918_직찍8.jpg",
                "created_at": "1 month ago"
            },
            {
                "id": 2373,
                "type": "fanfeed",
                "post": null,
                "title": "test7",
                "contents": "test7c",
                "thumbnail_url": "\/images\/fanfeed\/thumbnail\/krieshachu_fanfeed_1571039904_직찍7.jpg",
                "created_at": "1 month ago"
            },
            {
                "id": 2372,
                "type": "fanfeed",
                "post": null,
                "title": "test6",
                "contents": "test6c",
                "thumbnail_url": "\/images\/fanfeed\/thumbnail\/krieshachu_fanfeed_1571039891_직찍6.jpg",
                "created_at": "1 month ago"
            }
        ],
        "music_list": [
            {
                "id": 10,
                "type": "music",
                "post": null,
                "title": "Like Paradise (Prod. Flow Blow)",
                "contents": "",
                "thumbnail_url": "\/krieshachu\/images\/music\/logo\/krieshachu_music_1569504307_krieshachu.PNG",
                "created_at": "1 month ago"
            },
            {
                "id": 11,
                "type": "music",
                "post": null,
                "title": "천일의 사랑",
                "contents": "",
                "thumbnail_url": "\/krieshachu\/images\/music\/logo\/krieshachu_music_1569505596_KrieshaChu천일의사랑190926.png",
                "created_at": "1 month ago"
            },
            {
                "id": 12,
                "type": "music",
                "post": null,
                "title": "어머님이 누구니",
                "contents": "",
                "thumbnail_url": "\/krieshachu\/images\/music\/logo\/krieshachu_music_1569561467_k6top10part1.PNG",
                "created_at": "1 month ago"
            },
            {
                "id": 13,
                "type": "music",
                "post": null,
                "title": "흑백사진",
                "contents": "",
                "thumbnail_url": "\/krieshachu\/images\/music\/logo\/krieshachu_music_1569570065_vol11.PNG",
                "created_at": "1 month ago"
            }
        ]
    },
    "resultCode": {
        "code": 0,
        "message": "Success"
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/search</code></p>
<!-- END_f7828fe70326ce6166fdba9c0c9d80ed -->
<!-- START_fb549ff5c6709019d301e47f3fa8d911 -->
<h2>api/search/{type}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/search/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/search/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {
        "data": [],
        "current_page": 1,
        "last_page": 1
    },
    "resultCode": {
        "code": 0,
        "message": "Success"
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/search/{type}</code></p>
<!-- END_fb549ff5c6709019d301e47f3fa8d911 -->
<!-- START_45cf857beb84d8bbd4dc387a96098e89 -->
<h2>api/keywords</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/keywords" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/keywords"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {
        "keywords": [
            {
                "id": 11,
                "name": "213213123"
            },
            {
                "id": 10,
                "name": "12312321"
            },
            {
                "id": 9,
                "name": "SampleLaravel"
            },
            {
                "id": 8,
                "name": "Dream of Paradise"
            },
            {
                "id": 7,
                "name": "크리사츄"
            },
            {
                "id": 6,
                "name": "보고싶어"
            },
            {
                "id": 5,
                "name": "내일도"
            },
            {
                "id": 3,
                "name": "Chu"
            },
            {
                "id": 2,
                "name": "Kriesha"
            },
            {
                "id": 1,
                "name": "Kriesha Chu"
            }
        ]
    },
    "resultCode": {
        "code": 0,
        "message": "Success"
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/keywords</code></p>
<!-- END_45cf857beb84d8bbd4dc387a96098e89 -->
<!-- START_1f544a1178eab023baec610c9c1a7343 -->
<h2>api/keywords/{search}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/keywords/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/keywords/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "data": {
        "keywords": [
            {
                "id": 11,
                "name": "213213123"
            },
            {
                "id": 10,
                "name": "12312321"
            }
        ]
    },
    "resultCode": {
        "code": 0,
        "message": "Success"
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/keywords/{search}</code></p>
<!-- END_1f544a1178eab023baec610c9c1a7343 -->
<!-- START_84c6962ea9640282c5c1667472f97da9 -->
<h2>api/push_generator</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/push_generator" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/push_generator"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (500):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Server Error"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/push_generator</code></p>
<!-- END_84c6962ea9640282c5c1667472f97da9 -->
<!-- START_1e8c5f56e2aa4ac3ab6499ab52f8db70 -->
<h2>api/fanx/app/stats</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/fanx/app/stats" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/fanx/app/stats"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "result": "success",
    "errno": 0,
    "message": "success",
    "data": {
        "label": [
            "2019-11-08",
            "2019-11-09",
            "2019-11-10",
            "2019-11-11",
            "2019-11-12",
            "2019-11-13",
            "2019-11-14"
        ],
        "items": [
            {
                "name": "설치수",
                "count": 0,
                "items": [
                    {
                        "label": "2019-11-08",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-09",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-10",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-11",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-12",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-13",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-14",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    }
                ]
            },
            {
                "name": "삭제수",
                "count": 0,
                "items": [
                    {
                        "label": "2019-11-08",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-09",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-10",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-11",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-12",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-13",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-14",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    }
                ]
            },
            {
                "name": "잔존수",
                "count": 0,
                "items": [
                    {
                        "label": "2019-11-08",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-09",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-10",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-11",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-12",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-13",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    },
                    {
                        "label": "2019-11-14",
                        "count": 0,
                        "status": "none",
                        "amount": 0
                    }
                ]
            }
        ],
        "parameters": []
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/fanx/app/stats</code></p>
<!-- END_1e8c5f56e2aa4ac3ab6499ab52f8db70 -->
<!-- START_6b9a9906646351b0b547d62a911290cd -->
<h2>api/fanx/trend/keyword/stats</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/api/fanx/trend/keyword/stats" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/fanx/trend/keyword/stats"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">{
    "result": "success",
    "errno": 0,
    "message": "success",
    "data": {
        "label": [
            "2019-11-08",
            "2019-11-09",
            "2019-11-10",
            "2019-11-11",
            "2019-11-12",
            "2019-11-13",
            "2019-11-14"
        ],
        "items": [],
        "parameters": []
    }
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/fanx/trend/keyword/stats</code></p>
<!-- END_6b9a9906646351b0b547d62a911290cd -->
<!-- START_c161154ef2c3437801e77c71155a2134 -->
<h2>api/fanx/location/fan</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/fanx/location/fan" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/fanx/location/fan"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/fanx/location/fan</code></p>
<!-- END_c161154ef2c3437801e77c71155a2134 -->
<!-- START_b1e6866e0b9abb021261bdc466345ee8 -->
<h2>api/fanx/location/stalker</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/fanx/location/stalker" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/fanx/location/stalker"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/fanx/location/stalker</code></p>
<!-- END_b1e6866e0b9abb021261bdc466345ee8 -->
<!-- START_e6c4d575d34cbab1957204ee66331a68 -->
<h2>api/fanx/customer/request</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/api/fanx/customer/request" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/fanx/customer/request"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/fanx/customer/request</code></p>
<!-- END_e6c4d575d34cbab1957204ee66331a68 -->
<!-- START_78a3578b5e562271a90bd4e4fca6fde2 -->
<h2>api/fanx/customer/request/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/api/fanx/customer/request/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/api/fanx/customer/request/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE api/fanx/customer/request/{id}</code></p>
<!-- END_78a3578b5e562271a90bd4e4fca6fde2 -->
<!-- START_03a76d7b7a89853a08696bfe71bbbba7 -->
<h2>admin/login</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/login</code></p>
<!-- END_03a76d7b7a89853a08696bfe71bbbba7 -->
<!-- START_fe5fe3a14f04e5648848f1a59ea3da82 -->
<h2>admin/login</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/login</code></p>
<!-- END_fe5fe3a14f04e5648848f1a59ea3da82 -->
<!-- START_d31bd86158f6a5a775c92ea5b5554af9 -->
<h2>admin/logout</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/logout</code></p>
<!-- END_d31bd86158f6a5a775c92ea5b5554af9 -->
<!-- START_8c9f24dde799b3cc689733f481674a38 -->
<h2>admin/password/{email}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/password/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/password/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (200):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/password/{email}</code></p>
<!-- END_8c9f24dde799b3cc689733f481674a38 -->
<!-- START_5adb058bbb4c4cd169b5eea1c01c67f0 -->
<h2>admin/password</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/password</code></p>
<!-- END_5adb058bbb4c4cd169b5eea1c01c67f0 -->
<!-- START_e40bc60a458a9740730202aaec04f818 -->
<h2>admin</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin</code></p>
<!-- END_e40bc60a458a9740730202aaec04f818 -->
<!-- START_4741877e0ff2764ef5a6ca426c859c6a -->
<h2>admin/location/fan/stats</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/location/fan/stats" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/location/fan/stats"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/location/fan/stats</code></p>
<!-- END_4741877e0ff2764ef5a6ca426c859c6a -->
<!-- START_a9414b6a8dce1b6cb9a39f8c5c3fd71b -->
<h2>admin/location/stalker/stats</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/location/stalker/stats " \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/location/stalker/stats "
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (404):</p>
</blockquote>
<pre><code class="language-json">{
    "message": ""
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/location/stalker/stats</code></p>
<!-- END_a9414b6a8dce1b6cb9a39f8c5c3fd71b -->
<!-- START_7aa79c395bdb4a55af3100e8843d09e0 -->
<h2>admin/app/stats/installed</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/app/stats/installed" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/app/stats/installed"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/app/stats/installed</code></p>
<!-- END_7aa79c395bdb4a55af3100e8843d09e0 -->
<!-- START_3eb061a9ca55afe67383371ca5fe3043 -->
<h2>admin/app/stats/uninstalled</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/app/stats/uninstalled" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/app/stats/uninstalled"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/app/stats/uninstalled</code></p>
<!-- END_3eb061a9ca55afe67383371ca5fe3043 -->
<!-- START_4243d3645a24b93a7210b8f303a7db82 -->
<h2>admin/app/stats/used</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/app/stats/used" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/app/stats/used"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/app/stats/used</code></p>
<!-- END_4243d3645a24b93a7210b8f303a7db82 -->
<!-- START_193d2fcf87064ddd0ba843a9488787b0 -->
<h2>admin/trend/keyword/stats</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/trend/keyword/stats" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/trend/keyword/stats"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/trend/keyword/stats</code></p>
<!-- END_193d2fcf87064ddd0ba843a9488787b0 -->
<!-- START_e7382d8384d20e5c1ead10f2c1725820 -->
<h2>admin/fan/type/stats</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/fan/type/stats" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/fan/type/stats"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/fan/type/stats</code></p>
<!-- END_e7382d8384d20e5c1ead10f2c1725820 -->
<!-- START_817c9161df7d2cd3abbee68a2f839f1a -->
<h2>admin/fan/character/stats</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/fan/character/stats" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/fan/character/stats"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/fan/character/stats</code></p>
<!-- END_817c9161df7d2cd3abbee68a2f839f1a -->
<!-- START_46c68f1e164839ec930b45ff91314f08 -->
<h2>admin/customer/request</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/customer/request" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/customer/request"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/customer/request</code></p>
<!-- END_46c68f1e164839ec930b45ff91314f08 -->
<!-- START_a058554460c929e507aff509d48f8eb4 -->
<h2>admin/customer/error</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/customer/error" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/customer/error"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/customer/error</code></p>
<!-- END_a058554460c929e507aff509d48f8eb4 -->
<!-- START_a9c0da950fb1a9e1e770100bbdadd6e5 -->
<h2>admin/sample/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/sample/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/sample/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/sample/{id}</code></p>
<!-- END_a9c0da950fb1a9e1e770100bbdadd6e5 -->
<!-- START_1bc9669394e810afe6c5acb3cf3a00a4 -->
<h2>admin/boards</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/boards" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/boards</code></p>
<!-- END_1bc9669394e810afe6c5acb3cf3a00a4 -->
<!-- START_dd94c8f12dc1d257fe5661b295ea8ad9 -->
<h2>admin/boards/new</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/boards/new" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/new"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/boards/new</code></p>
<!-- END_dd94c8f12dc1d257fe5661b295ea8ad9 -->
<!-- START_b962b361ca0a9901d6724975bcc0047e -->
<h2>admin/boards/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/boards/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/boards/{id}</code></p>
<!-- END_b962b361ca0a9901d6724975bcc0047e -->
<!-- START_7f1dc6916f977c1530bd3bd13587ae5d -->
<h2>admin/boards</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/boards" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/boards</code></p>
<!-- END_7f1dc6916f977c1530bd3bd13587ae5d -->
<!-- START_63714615aa142a3a72f157572ee6e739 -->
<h2>admin/boards/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/boards/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/boards/{id}</code></p>
<!-- END_63714615aa142a3a72f157572ee6e739 -->
<!-- START_43c2473d0ef0d5b8bbb501e8a6558776 -->
<h2>admin/boards/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/admin/boards/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE admin/boards/{id}</code></p>
<!-- END_43c2473d0ef0d5b8bbb501e8a6558776 -->
<!-- START_51a6b3e5292b778dc26f29ad15638fa1 -->
<h2>admin/boards/bulk</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/admin/boards/bulk" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/bulk"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE admin/boards/bulk</code></p>
<!-- END_51a6b3e5292b778dc26f29ad15638fa1 -->
<!-- START_b290d34307474af98163d2f6ebc68183 -->
<h2>admin/boards/bulk/gender</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/boards/bulk/gender" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/bulk/gender"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/boards/bulk/gender</code></p>
<!-- END_b290d34307474af98163d2f6ebc68183 -->
<!-- START_7e483c37089a2835c41a48d462713868 -->
<h2>admin/boards/bulk/open</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/boards/bulk/open" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/bulk/open"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/boards/bulk/open</code></p>
<!-- END_7e483c37089a2835c41a48d462713868 -->
<!-- START_7f2c43f8298562c086d4b33c2e7da775 -->
<h2>admin/boards/bulk/text</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/boards/bulk/text" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/bulk/text"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/boards/bulk/text</code></p>
<!-- END_7f2c43f8298562c086d4b33c2e7da775 -->
<!-- START_a196f84bc392e6d4e1ed4a553d14ae11 -->
<h2>admin/boards/bulk/face</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/boards/bulk/face" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/bulk/face"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/boards/bulk/face</code></p>
<!-- END_a196f84bc392e6d4e1ed4a553d14ae11 -->
<!-- START_1b2a64c1a4b2e3763f0ff17c1d4dbc9e -->
<h2>admin/boards/bulk/tag</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/boards/bulk/tag" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/bulk/tag"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/boards/bulk/tag</code></p>
<!-- END_1b2a64c1a4b2e3763f0ff17c1d4dbc9e -->
<!-- START_2d982947ba1fc135bfd0309177987e90 -->
<h2>admin/boards/bulk/tag/common</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/boards/bulk/tag/common" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/bulk/tag/common"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/boards/bulk/tag/common</code></p>
<!-- END_2d982947ba1fc135bfd0309177987e90 -->
<!-- START_c828f4ca2808d5304cbc60725560c8a8 -->
<h2>admin/boards/bulk/app_review</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/boards/bulk/app_review" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/bulk/app_review"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/boards/bulk/app_review</code></p>
<!-- END_c828f4ca2808d5304cbc60725560c8a8 -->
<!-- START_5a0fbf83ea2c88388522ca39785282d0 -->
<h2>admin/boards/bulk/app_review</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/boards/bulk/app_review" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/bulk/app_review"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/boards/bulk/app_review</code></p>
<!-- END_5a0fbf83ea2c88388522ca39785282d0 -->
<!-- START_bbc3d612ea79e97c0db2b6394c97a61e -->
<h2>admin/boards/chart</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/boards/chart" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/chart"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/boards/chart</code></p>
<!-- END_bbc3d612ea79e97c0db2b6394c97a61e -->
<!-- START_a218a18d9716b50da2d51dd4480db10a -->
<h2>admin/boards/chart/data</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/boards/chart/data" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/boards/chart/data"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/boards/chart/data</code></p>
<!-- END_a218a18d9716b50da2d51dd4480db10a -->
<!-- START_be6b6bcefe4ea3b298b93f11fb79d83d -->
<h2>admin/musics</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/musics" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/musics"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/musics</code></p>
<!-- END_be6b6bcefe4ea3b298b93f11fb79d83d -->
<!-- START_b51271f397bc3f601ebf35f975b33ff9 -->
<h2>admin/musics</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/musics" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/musics"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/musics</code></p>
<!-- END_b51271f397bc3f601ebf35f975b33ff9 -->
<!-- START_f111e78ef0ef6983bdbbf3f51a772341 -->
<h2>admin/musics/{music_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/musics/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/musics/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/musics/{music_id}</code></p>
<!-- END_f111e78ef0ef6983bdbbf3f51a772341 -->
<!-- START_df0a4205d6cb30eb1d3b95b4e70e9c74 -->
<h2>admin/collect_batches</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/collect_batches" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/collect_batches"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/collect_batches</code></p>
<!-- END_df0a4205d6cb30eb1d3b95b4e70e9c74 -->
<!-- START_4eb3db45e4e5323e7b989b4414ee8a7b -->
<h2>admin/collect_batches/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/admin/collect_batches/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/collect_batches/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE admin/collect_batches/{id}</code></p>
<!-- END_4eb3db45e4e5323e7b989b4414ee8a7b -->
<!-- START_6c277b0d1d8e9bb3b13f905211abb28f -->
<h2>admin/collect_batches/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/collect_batches/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/collect_batches/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/collect_batches/{id}</code></p>
<!-- END_6c277b0d1d8e9bb3b13f905211abb28f -->
<!-- START_791621897a8463d4648689c91f2cfff5 -->
<h2>admin/collect_batches</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/collect_batches" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/collect_batches"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/collect_batches</code></p>
<!-- END_791621897a8463d4648689c91f2cfff5 -->
<!-- START_386d85ccf6dc7bf2ae2eba707a82b551 -->
<h2>admin/collect_batches/bulk/execute</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/collect_batches/bulk/execute" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/collect_batches/bulk/execute"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/collect_batches/bulk/execute</code></p>
<!-- END_386d85ccf6dc7bf2ae2eba707a82b551 -->
<!-- START_43fa820ad484bf20f51d0eea941f171b -->
<h2>배치 관리 - 풀 관리</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/azure" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/azure"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/azure</code></p>
<!-- END_43fa820ad484bf20f51d0eea941f171b -->
<!-- START_c691646f32adddcb436a5e9d14741785 -->
<h2>배치 관리 - 풀 등록</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/azure/pool" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/azure/pool"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/azure/pool</code></p>
<!-- END_c691646f32adddcb436a5e9d14741785 -->
<!-- START_e9ac909a2beedd2c642375ceb4088cad -->
<h2>배치 관리 - Jobs</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/azure/jobs" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/azure/jobs"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/azure/jobs</code></p>
<!-- END_e9ac909a2beedd2c642375ceb4088cad -->
<!-- START_1a9a251f2352d35a8fefdc394140213b -->
<h2>배치 관리 - Job &amp; Task 등록</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/azure/job" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/azure/job"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/azure/job</code></p>
<!-- END_1a9a251f2352d35a8fefdc394140213b -->
<!-- START_388b416fe1306b4d5f69dfcf0e00a3b8 -->
<h2>배치 관리 - Job Schedule</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/azure/jobschedule" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/azure/jobschedule"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/azure/jobschedule</code></p>
<!-- END_388b416fe1306b4d5f69dfcf0e00a3b8 -->
<!-- START_f8eb4f842fa8a654f98c7c7a28faa71f -->
<h2>배치 관리 - Job Schedule 등록</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/azure/jobschedule" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/azure/jobschedule"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/azure/jobschedule</code></p>
<!-- END_f8eb4f842fa8a654f98c7c7a28faa71f -->
<!-- START_0f46782d59d0109fda6679a6170131df -->
<h2>admin/collect_rules</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/collect_rules" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/collect_rules"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/collect_rules</code></p>
<!-- END_0f46782d59d0109fda6679a6170131df -->
<!-- START_2c048edbe4dd77c4b34d23c4ae03c059 -->
<h2>admin/banned_words</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/banned_words" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/banned_words"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/banned_words</code></p>
<!-- END_2c048edbe4dd77c4b34d23c4ae03c059 -->
<!-- START_7862be246ccde6ecd74c4d9120144b83 -->
<h2>admin/banned_words</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/banned_words" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/banned_words"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/banned_words</code></p>
<!-- END_7862be246ccde6ecd74c4d9120144b83 -->
<!-- START_957720d8fb0560fce01e91e5f1b4c8f9 -->
<h2>admin/banned_words/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/admin/banned_words/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/banned_words/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE admin/banned_words/{id}</code></p>
<!-- END_957720d8fb0560fce01e91e5f1b4c8f9 -->
<!-- START_d5780b37cf8a66783dedc0b1a3cf9597 -->
<h2>admin/recommend_tag</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/recommend_tag" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/recommend_tag"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/recommend_tag</code></p>
<!-- END_d5780b37cf8a66783dedc0b1a3cf9597 -->
<!-- START_a0b38f3cc52be3b24ab4bd7ee959a191 -->
<h2>admin/recommend_tag</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/recommend_tag" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/recommend_tag"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/recommend_tag</code></p>
<!-- END_a0b38f3cc52be3b24ab4bd7ee959a191 -->
<!-- START_e04b30d896771715806f6b8d0fc9114b -->
<h2>admin/recommend_tag/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/admin/recommend_tag/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/recommend_tag/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE admin/recommend_tag/{id}</code></p>
<!-- END_e04b30d896771715806f6b8d0fc9114b -->
<!-- START_7614490a3eef5fbcba402080d0369e6a -->
<h2>admin/users</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/users</code></p>
<!-- END_7614490a3eef5fbcba402080d0369e6a -->
<!-- START_07a2c34e3cc7acee537fdaad0c2c19d9 -->
<h2>admin/users/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/users/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/users/{id}</code></p>
<!-- END_07a2c34e3cc7acee537fdaad0c2c19d9 -->
<!-- START_962391d4c0bde76b28b720c41e1a6211 -->
<h2>admin/users/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/users/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/users/{id}</code></p>
<!-- END_962391d4c0bde76b28b720c41e1a6211 -->
<!-- START_95d9e9827ce8029da54f111fe63e62af -->
<h2>admin/standard</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/standard" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/standard"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/standard</code></p>
<!-- END_95d9e9827ce8029da54f111fe63e62af -->
<!-- START_8765314c24edd1b3a208c7224096f62c -->
<h2>admin/standard/{id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/standard/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/standard/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/standard/{id}</code></p>
<!-- END_8765314c24edd1b3a208c7224096f62c -->
<!-- START_87363960d1d4149d161396ed938353fc -->
<h2>admin/pushes</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/pushes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/pushes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/pushes</code></p>
<!-- END_87363960d1d4149d161396ed938353fc -->
<!-- START_e3d4736110d1c855af062eadcfb3b050 -->
<h2>admin/pushes/create</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/pushes/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/pushes/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/pushes/create</code></p>
<!-- END_e3d4736110d1c855af062eadcfb3b050 -->
<!-- START_f5044bc166725fc1837a650d37e62ff3 -->
<h2>admin/pushes</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/pushes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/pushes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/pushes</code></p>
<!-- END_f5044bc166725fc1837a650d37e62ff3 -->
<!-- START_cf16be262800f99b98990f0e7f380608 -->
<h2>admin/pushes/{push_id}/edit</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/pushes/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/pushes/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/pushes/{push_id}/edit</code></p>
<!-- END_cf16be262800f99b98990f0e7f380608 -->
<!-- START_54df515c95f42fb3d40a458d63ee5fb5 -->
<h2>admin/pushes/{push_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/pushes/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/pushes/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/pushes/{push_id}</code></p>
<!-- END_54df515c95f42fb3d40a458d63ee5fb5 -->
<!-- START_410b6caa9e20eb0ae9029ccbe766a012 -->
<h2>admin/pushes/{push_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/admin/pushes/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/pushes/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE admin/pushes/{push_id}</code></p>
<!-- END_410b6caa9e20eb0ae9029ccbe766a012 -->
<!-- START_6b5f45f2460bd2ae70f6d5618f1be7ee -->
<h2>admin/notices</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/notices" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/notices"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/notices</code></p>
<!-- END_6b5f45f2460bd2ae70f6d5618f1be7ee -->
<!-- START_e5df9a7b35024d29813fbccf92aba153 -->
<h2>admin/notices/bulk</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/admin/notices/bulk" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/notices/bulk"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE admin/notices/bulk</code></p>
<!-- END_e5df9a7b35024d29813fbccf92aba153 -->
<!-- START_57f0ea0d0e23a2717bf2248261d6e332 -->
<h2>admin/notices/{notice_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/notices/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/notices/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/notices/{notice_id}</code></p>
<!-- END_57f0ea0d0e23a2717bf2248261d6e332 -->
<!-- START_acd6a4623dd8486301f9eb19df713220 -->
<h2>admin/notices</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/notices" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/notices"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/notices</code></p>
<!-- END_acd6a4623dd8486301f9eb19df713220 -->
<!-- START_559be419d331903a95ea4cf7e0a6df07 -->
<h2>admin/schedules</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/schedules" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/schedules"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/schedules</code></p>
<!-- END_559be419d331903a95ea4cf7e0a6df07 -->
<!-- START_5bc791ad64b674d0202cf9d995fed7b9 -->
<h2>admin/schedules/bulk</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/admin/schedules/bulk" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/schedules/bulk"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE admin/schedules/bulk</code></p>
<!-- END_5bc791ad64b674d0202cf9d995fed7b9 -->
<!-- START_8da57a7ffb43bd198ff6098103aea059 -->
<h2>admin/schedules/{notice_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/schedules/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/schedules/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/schedules/{notice_id}</code></p>
<!-- END_8da57a7ffb43bd198ff6098103aea059 -->
<!-- START_33d59eac877e03936bd7ba1f07d8f992 -->
<h2>admin/schedules</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/schedules" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/schedules"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/schedules</code></p>
<!-- END_33d59eac877e03936bd7ba1f07d8f992 -->
<!-- START_689add0ab665f6887a9f08e2b63666bd -->
<h2>Display a listing of the resource.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/search/keyword" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/search/keyword"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/search/keyword</code></p>
<!-- END_689add0ab665f6887a9f08e2b63666bd -->
<!-- START_298fa9a5938b6cca191dafc4f5c408be -->
<h2>Show the form for creating a new resource.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/search/keyword/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/search/keyword/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/search/keyword/create</code></p>
<!-- END_298fa9a5938b6cca191dafc4f5c408be -->
<!-- START_1d3881eac3babb506fd31ccacef2742f -->
<h2>Store a newly created resource in storage.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/search/keyword" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/search/keyword"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/search/keyword</code></p>
<!-- END_1d3881eac3babb506fd31ccacef2742f -->
<!-- START_8999c055aa3782f1503ff0bc39078f8a -->
<h2>Display the specified resource.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/search/keyword/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/search/keyword/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/search/keyword/{keyword}</code></p>
<!-- END_8999c055aa3782f1503ff0bc39078f8a -->
<!-- START_5749072304b5ff88a304afd5773454c0 -->
<h2>Show the form for editing the specified resource.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/search/keyword/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/search/keyword/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/search/keyword/{keyword}/edit</code></p>
<!-- END_5749072304b5ff88a304afd5773454c0 -->
<!-- START_72817135265451c427064304377cdcb9 -->
<h2>Update the specified resource in storage.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/search/keyword/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/search/keyword/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/search/keyword/{keyword}</code></p>
<p><code>PATCH admin/search/keyword/{keyword}</code></p>
<!-- END_72817135265451c427064304377cdcb9 -->
<!-- START_c254b80638c34c2403b77a7b5a12ac16 -->
<h2>Remove the specified resource from storage.</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/admin/search/keyword/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/search/keyword/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE admin/search/keyword/{keyword}</code></p>
<!-- END_c254b80638c34c2403b77a7b5a12ac16 -->
<!-- START_78763498aadc4f9449f761d76bb177de -->
<h2>admin/campaigns</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/campaigns" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/campaigns"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/campaigns</code></p>
<!-- END_78763498aadc4f9449f761d76bb177de -->
<!-- START_f773b1005a72684f981ce60580ae0590 -->
<h2>admin/campaigns/create</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/campaigns/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/campaigns/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/campaigns/create</code></p>
<!-- END_f773b1005a72684f981ce60580ae0590 -->
<!-- START_744daae3cad5e5f9f591974c4d5ad41f -->
<h2>admin/campaigns</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "/admin/campaigns" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/campaigns"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST admin/campaigns</code></p>
<!-- END_744daae3cad5e5f9f591974c4d5ad41f -->
<!-- START_1356437b95c481ff89681298024191e4 -->
<h2>admin/campaigns/{push_id}/edit</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "/admin/campaigns/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/campaigns/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (302):</p>
</blockquote>
<pre><code class="language-json">null</code></pre>
<h3>HTTP Request</h3>
<p><code>GET admin/campaigns/{push_id}/edit</code></p>
<!-- END_1356437b95c481ff89681298024191e4 -->
<!-- START_c42932c59817940aa505819337e77c81 -->
<h2>admin/campaigns/{push_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "/admin/campaigns/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/campaigns/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT admin/campaigns/{push_id}</code></p>
<!-- END_c42932c59817940aa505819337e77c81 -->
<!-- START_9248d66cd33a1c34f0494ed2306bc217 -->
<h2>admin/campaigns/{push_id}</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X DELETE \
    "/admin/campaigns/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "/admin/campaigns/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>DELETE admin/campaigns/{push_id}</code></p>
<!-- END_9248d66cd33a1c34f0494ed2306bc217 -->
      </div>
      <div class="dark-box">
                        <div class="lang-selector">
                                    <a href="#" data-language-name="bash">bash</a>
                                    <a href="#" data-language-name="javascript">javascript</a>
                              </div>
                </div>
    </div>
  </body>
</html>