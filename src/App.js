import {
  useEntityProp,
  store as coreStore,
} from "@wordpress/core-data";
import { useDispatch } from "@wordpress/data";
import { Button, CheckboxControl } from "@wordpress/components";

const OPTIONS_KEYS = {
  enableCSS: "bootstrap_enable_css",
  enableJS: "bootstrap_enable_js",
}

export default function App() {
  const [enableCSS, setEnableCSS] = useEntityProp("root", "site", OPTIONS_KEYS.enableCSS);
  const [enableJS, setEnableJS] = useEntityProp("root", "site", OPTIONS_KEYS.enableJS);

  const { saveEditedEntityRecord } = useDispatch(coreStore);
  const saveOptions = () => {
    saveEditedEntityRecord("root", "site", undefined, {
      [OPTIONS_KEYS.enableCSS]: enableCSS,
      [OPTIONS_KEYS.enableJS]: enableJS
    });
  };

  return (
    <div>
      <h2>Settings</h2>
      <div>
        <CheckboxControl
          label="Enable CSS"
          checked={enableCSS ? enableJS : false}
          onChange={(newValue) => setEnableCSS(newValue)}
        />
        <CheckboxControl
          label="Enable JS"
          checked={enableJS ? enableJS : false}
          onChange={(newValue) => setEnableJS(newValue)}
        />
      </div>
      <Button
        variant="primary"
        onClick={() => saveOptions()}
      >Save</Button>
    </div>
  );
}
