<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Starquess Videoconferință</title>

    <head>
      <!--
        For the UI Kit and Web Core script tags,
        make sure to use the latest versions in the URL when you're using this
      -->

      <!-- Import helper to load UI Kit components -->
      <script type="module">
        import { defineCustomElements } from 'https://cdn.jsdelivr.net/npm/@dytesdk/ui-kit@1.43.1/loader/index.es2017.js';
        defineCustomElements();
      </script>

      <!-- Import Web Core via CDN too -->
      <script src="https://cdn.dyte.in/core/dyte-1.4.0.js"></script>
    </head>
  </head>
  <body>
    <dyte-meeting id="my-meeting" show-setup-screen="true" />

    <script>
      const searchParams = new URL(window.location.href).searchParams;

      const authToken = searchParams.get('authToken');

      // pass an empty string when using v2 meetings
      // for v1 meetings, you would need to pass the correct roomName here
      const roomName = searchParams.get('roomName') || '';

      if (!authToken) {
        alert(
          "An authToken wasn't passed, please pass an authToken in the URL query to join a meeting."
        );
      }

      // Initialize a meeting
      DyteClient.init({
        authToken,
        roomName,
      }).then((meeting) => {
        document.getElementById('my-meeting').meeting = meeting;
      });
    </script>
  </body>
</html>