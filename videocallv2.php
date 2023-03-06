<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Simple Background Transformer sample | Dyte UI Kit</title>

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

    <!-- Video Background Transformer -->
    <script src="https://cdn.jsdelivr.net/npm/@dytesdk/video-background-transformer/dist/index.iife.js"></script>
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
      }).then(async (meeting) => {
        document.getElementById('my-meeting').meeting = meeting;

        const videoBackgroundTransformer =
          await DyteVideoBackgroundTransformer.init();

        // The video-background-transformer provides two functionalities
        // 1. Add background blur
        // 2. Add a background image

        // 1. To add background blur, with strength of 10
        // meeting.self.addVideoMiddleware(
        //   await videoBackgroundTransformer.createBackgroundBlurVideoMiddleware(
        //     10
        //   )
        // );

        // 2. To add a background image
        meeting.self.addVideoMiddleware(
          await videoBackgroundTransformer.createStaticBackgroundVideoMiddleware(
            'https://assets.dyte.io/backgrounds/bg-dyte-office.jpg'
          )
        );

        // We have the following set of images for your immediate use:
        // https://assets.dyte.io/backgrounds/bg-dyte-office.jpg
        // https://assets.dyte.io/backgrounds/bg_0.jpg
        // https://assets.dyte.io/backgrounds/bg_1.jpg
        // https://assets.dyte.io/backgrounds/bg_2.jpg
        // https://assets.dyte.io/backgrounds/bg_3.jpg
        // https://assets.dyte.io/backgrounds/bg_4.jpg
        // https://assets.dyte.io/backgrounds/bg_5.jpg
        // https://assets.dyte.io/backgrounds/bg_6.jpg
        // https://assets.dyte.io/backgrounds/bg_7.jpg
      });
    </script>
  </body>
</html>