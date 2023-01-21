import { render } from "@wordpress/element";

import App from "./App";

addEventListener("DOMContentLoaded", () => {
  const $appContainer = document.getElementById("bootstrap-settings");

  render(
    <App />,
    $appContainer
  );
});
