import {
  useEntityProp,
  store as coreStore,
} from "@wordpress/core-data";
import { useDispatch } from "@wordpress/data";
import { Button, CheckboxControl } from "@wordpress/components";

const OPTIONS_KEYS = {
  enableCSS: "bootstrap_enable_css",
}

export default function App() {
  const [enableCSS, setEnableCSS] = useEntityProp("root", "site", OPTIONS_KEYS.enableCSS);

  const { saveEditedEntityRecord } = useDispatch(coreStore);
  const saveOptions = () => {
    saveEditedEntityRecord("root", "site", undefined, {
      [OPTIONS_KEYS.enableCSS]: enableCSS,
    });
  };

  return (
    <div>
      <h2>Settings</h2>
      <div>
        <CheckboxControl
          label="Enable CSS"
          checked={enableCSS | false}
          onChange={(newValue) => setEnableCSS(newValue)}
        />
      </div>
      <Button
        variant="primary"
        onClick={() => saveOptions()}
      >Save</Button>
    </div>
  );
}
