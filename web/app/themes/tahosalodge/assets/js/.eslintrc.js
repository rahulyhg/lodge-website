module.exports = {
    "env": {
        "browser": true
    },
	"globals": {
		"jQuery": 1,
		"$": 1
	},
    "extends": "eslint:recommended",
    "rules": {
        "indent": [
            "error",
            4
        ],
        "linebreak-style": [
            "error",
            "unix"
        ],
        "quotes": [
            "error",
            "single"
        ],
        "semi": [
            "error",
            "always"
        ]
    }
};
