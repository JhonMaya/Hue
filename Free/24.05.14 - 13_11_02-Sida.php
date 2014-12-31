<?php exit() ?>--by Sida 81.170.70.121
			SPELL_TARGETED = 1
			SPELL_LINEAR = 2
			SPELL_CIRCLE = 3
			SPELL_CONE = 4
			SPELL_LINEAR_COL = 5
			SPELL_SELF = 6
			SPELL_SELF_AT_MOUSE = 7

_G.rebornskillslist = {
	-- {"[ChampName]", true, [key], [range], [skill name], [type], 0, [after attack], [aa range only], [speed], [delay], [width], false, false},
-- Aatrox
{"Aatrox",			true, _E,  1000, "E (Blades of Torment)",			SPELL_LINEAR,		0, false, false,  1100, 0.50, 100, false, false},

-- Ahri
{"Ahri",			true, _Q,	880, "Q (Orb of Deception)",			SPELL_LINEAR,		0, false, false,  1100, 0.50, 100, false, false},
{"Ahri",			true, _W,	750, "W (Fox-Fire)",					SPELL_SELF,			0, false, false,  1400, 0.50,	0, false, false},
{"Ahri",			true, _E,	975, "E (Charm)",						SPELL_LINEAR_COL,	0, false, false,  1200, 0.50,  60, false, false},

-- Akali
{"Akali",			true, _Q,	600, "Q (Mark of the Assassin)",		SPELL_TARGETED,		0, false, false,  1000, 0.65,	0, false, false},
{"Akali",			true, _E,	325, "E (Crescent Slash)",				SPELL_SELF,			0, false, false,     0, 0.50,	0, false, false},
{"Akali",			true, _R,	800, "R (Shadow Dance)",				SPELL_TARGETED,		0, false, false,  2200, 0   ,	0, false, false},

-- Alistar
{"Alistar", 		true, _Q,	365, "Q (Pulverize)",					SPELL_SELF,			0, false, false,  20  , 0.50,	0, false, false},
{"Alistar", 		true, _W,	650, "W (Headbutt)",					SPELL_TARGETED,		0, false, false,   0  , 0.55,	0, false, false},
{"Alistar", 		true, _R,	  0, "R (Unbreakable Will)",			SPELL_SELF,			0, false, true,  828.5, 0.50,	0, false, false},

-- Amumu
{"Amumu",			true, _Q,  1100, "Q (Bandage Toss)",				SPELL_LINEAR_COL,	0, false, false,  2000, 0.50,  80, false, false},
{"Amumu",			true, _E,	350, "E (Tantrum)",						SPELL_SELF,			0, false, false,     0, 0.50,	0, false, false},

-- Anivia
{"Anivia",			true, _E,	650, "E (Frostbite)",					SPELL_TARGETED,		0, false, false,  1200, 0.50,	0, false, false},
{"Anivia",			true, _R,	625, "R (Glacial Storm)",				SPELL_CIRCLE,		0, false, false,    20, 0.33, 150, false, false},

-- Annie
{"Annie",			true, _Q,	625, "Q (Disintegrate)",				SPELL_TARGETED,		0, false, false,  1400, 0.50,	0, false, false},
{"Annie",			true, _W,	625, "W (Incinerate)",					SPELL_CONE,			0, false, false,     0, 0.50,	0, false, false},
{"Annie",			true, _R,	600, "R (Summon: Tibbers)",				SPELL_CIRCLE,		0, false, false,     0, 0.50,	0, false, false},

-- Ashe
{"Ashe",			true, _W,	600, "W (Volley)",						SPELL_LINEAR_COL,	0, false, false,   902, 0.50,  80, false, false},
{"Ashe",			true, _R, 25000, "R (Enchanted Crystal Arrow)",		SPELL_LINEAR,		0, false, false,  1600, 0.50, 130, false, false},

-- Blitzcrank
{"Blitzcrank",		true, _Q,	925, "Q (Rocket Grab)",					SPELL_LINEAR_COL,	0, false, false,  1800, 0.50,  70, false, false},
{"Blitzcrank",		true, _R,	600, "R (Static Field)",				SPELL_SELF,			0, false, false,  1500, 0.50, 200, false, false},

-- Brand
{"Brand",			true, _Q,	900, "Q (Sear)",						SPELL_LINEAR_COL,	0, false, false,  1200, 0.50,  80, false, false},
{"Brand",			true, _W,	200, "W (Pillar of Flame)",				SPELL_CIRCLE,		0, false, false,	20, 0.50,	0, false, false},
{"Brand",			true, _E,	625, "E (Conflagration)",				SPELL_TARGETED,		0, false, false,  1400, 0.50,	0, false, false},
{"Brand",			true, _R,	750, "R (Pyroclasm)",					SPELL_TARGETED,		0, false, false,  1000, 0.50,	0, false, false},

-- Caitlyn
{"Caitlyn",			true, _Q,  1300, "Q (Piltover Peacemaker)",			SPELL_LINEAR,		0, false, false,  2200, 0.50,  90, false, false},
{"Caitlyn",			true, _E,  1000, "E (90 Caliber Net)",				SPELL_LINEAR_COL,	0, false, false,  2000, 0.70,  80, false, false},
{"Caitlyn",			true, _R,  2200, "R (Ace in the Hole)",				SPELL_TARGETED,		0, false, false,  1500, 0.50,	0, false, false},

-- Cassiopeia
{"Cassiopeia",		true, _Q,	850, "Q (Noxious Blast)",				SPELL_CIRCLE,		0, false, false,	 0, 0.50,	0, false, false},
{"Cassiopeia",		true, _W,	850, "W (Miasma)",						SPELL_CIRCLE,		0, false, false,  2500, 0.50,  90, false, false},
{"Cassiopeia",		true, _E,	700, "E (Twin Fang)",					SPELL_TARGETED,		0, false, false,  1900, 0.75,	0, false, false},
{"Cassiopeia",		true, _R,	825, "R (Petrifying Gaze)",				SPELL_CONE,			0, false, false,	 0, 0.75,	0, false, false},

-- Cho'Gath
{"Chogath",			true, _Q,	950, "Q (Rupture)",						SPELL_CIRCLE,		0, false, false,	 0, 0	,	0, false, false},
{"Chogath",			true, _W,	300, "W (Feral Scream)",				SPELL_CONE,			0, false, false,	 0, 0	,	0, false, false},
{"Chogath",			true, _R,	150, "R (Feast)",						SPELL_TARGETED,		0, false, false,   500, 0	,	0, false, false},

-- Corki
{"Corki",			true, _Q,	825, "Q (Phosphorus Bomb)",				SPELL_CIRCLE,		0, false, false,	 0, 0.50,	0, false, false},
{"Corki",			true, _W,	800, "W (Valkyrie)",					SPELL_LINEAR,		0, false, false,  1200, 0.50,  90, false, false},
{"Corki",			true, _E,	600, "E (Gatling Gun)",					SPELL_CONE,			0, false, false,   902, 0.50,	0, false, false},
{"Corki",			true, _R,  1225, "R (Missile Barrage)",				SPELL_LINEAR_COL,	0, false, false, 828.5, 0.50,  40, false, false},

-- Darius
{"Darius",			true, _Q,	270, "Q (Decimate)",					SPELL_SELF,			0, false, false,	 0, 0.50,  80, false, false},
{"Darius",			true, _W,	145, "W (Crippling Strike)",			SPELL_SELF,			0, true,  true,		 0, 0.23,	0, false, false},
{"Darius",			true, _E,	550, "E (Apprehend)",					SPELL_CONE,			0, false, false,  1500, 0.50,	0, false, false},
{"Darius",			true, _R,	475, "R (Noxian Guillotine)",			SPELL_TARGETED,		0, false, false,    20, 0.50,	0, false, false},

-- Diana
{"Diana",			true, _Q,	830, "Q (Crescent Strike)",				SPELL_LINEAR,		0, false, false,  2000, 0.50,	0, false, false},
{"Diana",			true, _W,	625, "W (Pale Cascade)",				SPELL_SELF,			0, false, false,  1400, 0.50,	0, false, false},
{"Diana",			true, _R,	825, "R (Lunar Rush)",					SPELL_TARGETED,		0, false, false,     0, 0.50,	0, false, false},

-- Dr.Mundo
{"DrMundo",			true, _Q,  1000, "Q (Infected Cleaver)",			SPELL_LINEAR_COL,	0, false, false,  1500, 0.50,  75, false, false},

-- Draven
{"Draven",			true, _E,  1050, "E (Stand Aside)",					SPELL_LINEAR,		0, false, false,  1600, 0.50, 130, false, false},
{"Draven",			true, _R, 20000, "R (Whirling Death)",				SPELL_LINEAR,		0, false, false,  2000, 0.50, 160, false, false},

-- Elise
{"Elise",			true, _Q,	625, "Q (Neurotoxin)",					SPELL_TARGETED,		0, false, false,  2200, 0.75,	0, false, false},
{"Elise",			true, _W,	950, "W (Volatile Spiderling)",			SPELL_SELF,			0, false, false,	 0, 0.75,	0, false, false},
{"Elise",			true, _E,  1100, "E (Cocoon)",						SPELL_TARGETED,		0, false, false,  1400, 0.50,  70, false, false},

-- EliseSpider
{"EliseSpider",		true, _Q,	475, "Q (Venomous Bite)",				SPELL_TARGETED,		0, false, false,    20, 0.75,	0, false, false},
{"EliseSpider",		true, _W,	700, "W (Skittering Frenzy)",			SPELL_SELF,			0, false, true,		 0, 0   ,	0, false, false},
{"EliseSpider",		true, _E,	925, "E (Rappel)",						SPELL_TARGETED,		0, false, false,	 0, 0   ,	0, false, false},

-- Evelynn
{"Evelynn",			true, _Q,	500, "Q (Hate Spike)",					SPELL_SELF,			0, false, false,    20, 0.50,	0, false, false},
{"Evelynn",			true, _E,	225, "E (Ravage)",						SPELL_TARGETED,		0, false, false,   900, 0.50,	0, false, false},
{"Evelynn",			true, _R,	650, "R (Agony's Embrace)",				SPELL_LINEAR_COL,	0, false, false,  1300, 0.50,  80, false, false},

-- Ezreal
{"Ezreal",			true, _Q,  1100, "Q (Mystic Shot)",					SPELL_LINEAR_COL,	0, false, false,  1200, 0.50,  80, false, false},
{"Ezreal",			true, _W,  1000, "W (Essence Flux)",				SPELL_LINEAR,		0, false, false,  1200, 0.50,  80, false, false},
{"Ezreal",			true, _E,	475, "E (Arcane Shift)",				SPELL_CIRCLE,		0, false, false,     0, 0.50,	0, false, false},
{"Ezreal",			true, _R, 25000, "R (Trueshot Barrage)",			SPELL_LINEAR,		0, false, false,  2000, 0.50, 160, false, false},

-- Fiddlesticks
{"FiddleSticks",	true, _Q,	575, "Q (Terrify)",						SPELL_TARGETED,		0, false, false,	 0, 0.50,	0, false, false},
{"FiddleSticks",	true, _W,	575, "W (Drain)",						SPELL_TARGETED,		0, false, false,	 0, 0.50,	0, false, false},
{"FiddleSticks",	true, _E,	750, "E (Dark Wind)",					SPELL_TARGETED,		0, false, false,  1100, 0.50,	0, false, false},

-- Fiora
{"Fiora",			true, _Q,	600, "Q (Lunge)",						SPELL_TARGETED,		0, false, false,  2200, 0	,	0, false, false},
{"Fiora",			true, _E,	500, "E (Burst of Speed)",				SPELL_SELF,			0, true,  true,		 0, 0.23,	0, false, false},
{"Fiora",			true, _R,	400, "R (Blade Waltz)",					SPELL_TARGETED,		0, false, false,	 0, 0.50,	0, false, false},

-- Fizz
{"Fizz",			true, _Q,	550, "Q (Urchin Strike)",				SPELL_TARGETED,		0, false, false,	 0, 0.50,	0, false, false},
{"Fizz",			true, _W,	600, "W (Seastone Trident)",			SPELL_SELF,			0, false, true,   1400, 0.50,  80, false, false},
{"Fizz",			true, _R,  1300, "R (Chum the Waters)",				SPELL_LINEAR,		0, false, false,  1200, 0.50,  80, false, false},

-- Galio
{"Galio",			true, _Q,	900, "Q (Resolute Smite)",				SPELL_CIRCLE,		0, false, false,  1300, 0.50, 120, false, false},
{"Galio",			true, _E,  1180, "E (Righteous Gust)",				SPELL_LINEAR,		0, false, false,  1200, 0.50, 120, false, false},

-- Gangplank
{"Gangplank",		true, _Q,	625, "Q (Parrrley)",					SPELL_TARGETED,		0, false, false,  2000, 0.50,	0, false, false},

-- Garen
{"Garen",			true, _Q,	300, "Q (Decisive Strike)",				SPELL_SELF,			0, false, true,		 0, 0.23,	0, false, false},
{"Garen",			true, _E,	165, "E (Judgment)",					SPELL_SELF,			0, false, false,   700, 0	, 160, false, false},
{"Garen",			true, _R,   400, "R (Demacian Justice)",			SPELL_TARGETED,		0, false, false,   900, 0.13, 120, false, false},

-- Gragas
{"Gragas",			true, _Q,	850, "Q (Barrel Roll)",					SPELL_CIRCLE,		0, false, false,  1000, 0.50, 110, false, false},
{"Gragas",			true, _E,	600, "E (Body Slam)",					SPELL_LINEAR_COL,	0, false, false,	20, 0.50,  50, false, false},
{"Gragas",			true, _R,  1050, "R (Explosive Cask)",				SPELL_CIRCLE,		0, false, false,   200, 0.50, 120, false, false},

-- Graves
{"Graves",			true, _Q,	700, "Q (Buckshot)",					SPELL_CONE,			0, false, false,   902, 0.50,	0, false, false},
{"Graves",			true, _W,	900, "W (Smoke Screen)",				SPELL_CIRCLE,		0, false, false,  1650, 0.50,	0, false, false},
{"Graves",			true, _R,  1000, "R (Collateral Damage)",			SPELL_LINEAR_COL,	0, false, false,  1200, 0.50, 100, false, false},

-- Hecarim
{"Hecarim",			true, _Q,	350, "Q (Rampage)",						SPELL_SELF,			0, false, false,   1450, 0.30,	0, false, false},
{"Hecarim",			true, _W,	575, "W (Spirit of Dread)",				SPELL_SELF,			0, false, false, 828.5, 0.43,	0, false, false},

-- Heimerdinger
{"Heimerdinger",	true, _W,  1100, "W (Hextech Micro-Rockets)",		SPELL_LINEAR_COL,	0, false, false,   902, 0.50, 200, false, false},
{"Heimerdinger",	true, _E,	925, "E (CH-2 Electron Storm Grenade)",	SPELL_CIRCLE,		0, false, false,  2500, 0.50, 120, false, false},

-- Irelia
{"Irelia",			true, _Q,	650, "Q (Bladesurge)",					SPELL_TARGETED,		0, false, false,  3200, 0	,	0, false, false},
{"Irelia",			true, _W,	 20, "W (Hiten Style)",					SPELL_SELF,			0, false, true,  347.8, 0.23,	0, false, false},
{"Irelia",			true, _E,	425, "E (Equilibrium Strike)",			SPELL_TARGETED,		0, false, false,     0, 0.50,	0, false, false},
{"Irelia",			true, _R,  1200, "R (Transcendent Blades)",			SPELL_LINEAR,		0, false, false,  1500, 0.50,  70, false, false},

-- Janna
{"Janna",			true, _Q,  1700, "Q (Howling Gale)",				SPELL_LINEAR,		0, false, false,	 0, 0	, 200, false, false},
{"Janna",			true, _W,	600, "W (Zephyr)",						SPELL_TARGETED,		0, false, false,  1600, 0.50,	0, false, false},

-- Jarvan
{"Jarvan",			true, _Q,	770, "Q (Dragon Strike)",				SPELL_LINEAR,		0, false, false,	20, 0.50,  70, false, false},
{"Jarvan",			true, _W,	525, "W (Golden Aegis)",				SPELL_SELF,			0, false, false,  1500, 0.75,	0, false, false},
{"Jarvan",			true, _E,	830, "E (Demacian Standard)",			SPELL_CIRCLE,		0, false, false,  1450, 0.50,	0, false, false},
{"Jarvan",			true, _R,   650, "R (Cataclysm)",					SPELL_TARGETED,		0, false, false,	 0, 0.50,	0, false, false},

-- Jax
{"Jax",				true, _Q,	700, "Q (Leap Strike)",					SPELL_TARGETED,		0, false, false,	 0, 0.50,	0, false, false},
{"Jax",				true, _W,	300, "W (Empower)",						SPELL_SELF,			0, false, true,		 0, 0.23,	0, false, false},

-- Jinx
{"Jinx",			true, _W,	1500, "W (Zap!)",						SPELL_LINEAR_COL,	0, false, false,  3200, 0.30,	0, false, false},
{"Jinx",			true, _R,  25000, "R (Super Mega Death Rocket!)",	SPELL_LINEAR,		0, false, false,  1600, 0.50, 225, false, false}, -- Note: Increases in speed after 1500 units.

-- Karma
{"Karma",			true, _Q,	950, "Q (Inner Flame)",					SPELL_LINEAR_COL,	0, false, false,  900,  0.30,  70, false, false},
{"Karma",			true, _Q,	950, "Q (Soulflare)",					SPELL_LINEAR_COL,	0, false, false,  900,  0.30,  70, false, false},
{"Karma",			true, _W,	675, "W (Focused Resolve)",				SPELL_TARGETED,		0, false, false,    0,  0.30,	0, false, false},
{"Karma",			true, _W,	675, "W (Renewal)",						SPELL_TARGETED,		0, false, false,	0,  0.30,	0, false, false},

-- Karthus
{"Karthus",			true, _Q,	875, "Q (Laywast)",						SPELL_CIRCLE,		0, false, false,	0,  0.30,  50, false, false},

-- Kassadin
{"Kassadin",		true, _Q,	650, "Q (Null Sphere)",					SPELL_TARGETED,		0, false, false,  800,  0.30,	0, false, false},
{"Kassadin",		true, _W,	150, "W (Nether Blade)",				SPELL_SELF,			0, true, true,		0,  0   ,	0, false, false},
{"Kassadin",		true, _E,	700, "E (Force Pulse)",					SPELL_CONE,			0, false, false,  800,  0.30,	0, false, false},
{"Kassadin",		true, _R,	700, "R (Nether Blade)",				SPELL_CIRCLE,		0, false, false,	0,  0.30,	0, false, false},

-- Katarina
{"Katarina",		true, _Q,	675, "Q (Bouncing Blades)",				SPELL_TARGETED,		0, false, false, 1200,  0.30,	0, false, false},
{"Katarina",		true, _W,	375, "W (Sinister Steel)",				SPELL_SELF,			0, false, false,	0,  0   ,	0, false, false},
{"Katarina",		true, _E,	700, "E (Shunpo)",						SPELL_TARGETED,		0, false, false,    0,  0.30,	0, false, false},

-- Kayle
{"Kayle",			true, _Q,	620, "Q (Reckoning)",					SPELL_TARGETED,		0, false, false, 1500,  0.30,	0, false, false},
{"Kayle",			true, _W,	525, "W (Righteous Fury)",				SPELL_SELF,			0, false, true,		0,  0   ,	0, false, false},

-- Kennen
{"Kennen",			true, _Q,  1050, "Q (Thundering Shuriken)",			SPELL_LINEAR_COL,	0, false, false, 1300,  0.30,	0, false, false},
{"Kennen",			true, _W,	800, "W (Electrical Surge)",			SPELL_SELF,			0, false, false,	0,  0.30,	0, false, false},
{"Kennen",			true, _E,	  0, "E (Lightning Rush)",				SPELL_SELF,			0, false, false,	0,  0.30,	0, false, false},

-- Kha'Zix
{"Kha'Zix",			true, _Q,	325, "Q (Taste Their Fear)",			SPELL_TARGETED,		0, true, false,		0,  0.30,	0, false, false},
{"Kha'Zix",			true, _Q,	375, "Q (Evolved Enlarged Claws)",		SPELL_TARGETED,		0, true, false,		0,  0.30,	0, false, false},
{"Kha'Zix",			true, _W,  1000, "W (Void Spike)",					SPELL_LINEAR_COL,	0, false, false,  900,  0.30,	0, false, false},
{"Kha'Zix",			true, _W,  1000, "W (Evolved Spike Racks)",			SPELL_LINEAR_COL,	0, false, false,  900,  0.30,	0, false, false},
{"Kha'Zix",			true, _E,	600, "E (Leap)",						SPELL_CIRCLE,		0, false, false,  700,  0   ,	0, false, false},
{"Kha'Zix",			true, _E,	900, "E (Evolved Wings)",				SPELL_CIRCLE,		0, false, false,  700,  0   ,	0, false, false},

-- Kog'Maw
{"Kog'Maw",			true, _Q,  1000, "Q (Caustic Spittle)",				SPELL_TARGETED,		0, false, false, 1200,  0.30,	0, false, false},
{"Kog'Maw",			true, _W,	  0, "W (Bio-Arcane Barrage)",			SPELL_SELF,			0, false, false,	0,  0   ,	0, false, false},
{"Kog'Maw",			true, _E,  1280, "E (Void Ooze)",					SPELL_LINEAR,		0, false, false,  800,  0.30, 125, false, false},

-- LeBlanc
{"LeBlanc",			true, _Q,	700, "Q (Sigil of Silence)",			SPELL_TARGETED,		0, false, false, 1200,  0.20,	0, false, false},
{"LeBlanc",			true, _E,	950, "E (Ethereal Chains)",				SPELL_LINEAR_COL,	0, false, false,  700,  0.20,  70, false, false},

-- Lee Sin
{"Lee Sin",			true, _Q,  1100, "Q (Sonic Wave)",					SPELL_LINEAR_COL,	0, false, false,   900, 0.25, 70,  false, false}, -- Note: Only to assist to hit with the first Q, second is activated by user.
{"Lee Sin",			true, _E,	350, "E (Tempest)",						SPELL_SELF,			0, false, true,		 0, 0.40,  0,  false, false}, -- Note: Only to let user need to hit E once.

-- Leona
{"Leona",			true, _Q,	125, "Q (Shield of Daybreak)",			SPELL_LINEAR,		0, false, false,     0, 0   ,  0,  false, false},
{"Leona",			true, _Q,	875, "E (Zenith Blade)",				SPELL_LINEAR,		0, false, false,   800, 0.20, 50,  false, false},

-- Lissandra
{"Lissandra",		true, _Q,   725, "Q (Ice Shard)",					SPELL_LINEAR,		0, false, false,   800, 0.30,  70, false, false},
{"Lissandra", 		true, _W, 	450, "W (Ring of Frost)",	 			SPELL_SELF, 		0, false, false,	 0, 0   , 	0, false, false},

-- Lucian
{"Lucian", 			true, _Q, 	550, "Q (Piercing Light)", 				SPELL_TARGETED, 	0, false, false, 	 0, 0.30, 60,  false, false},
{"Lucian", 			true, _W,  1000, "W (Ardent Blaze)", 				SPELL_LINEAR_COL, 	0, false, false,   800, 0.35, 90,  false, false},

-- Lulu
{"Lulu",			true, _Q,	925, "Q (Glitterlance)",				SPELL_LINEAR,		0, false, false,   800, 0.40, 70,  false, false},

-- Lux
{"Lux", 			true, _Q,  1175, "Q (Light Binding)", 				SPELL_LINEAR_COL, 	0, false, false,  1200, 0.35, 90,  false, false},
{"Lux",				true, _W,  1075, "W (Prismatic Barrier)",			SPELL_LINEAR,		0, false, false,   900, 0.20, 50,  false, false},
{"Lux",				true, _E,  1100, "E (Lucent Singularity)",			SPELL_CIRCLE,		0, false, false,  1200, 0.30,300,  false, false},

-- Malphite
{"Malphite", 		true, _Q, 	625, "Q (Seismic Shard)", 				SPELL_TARGETED, 	0, false, false, 	 0, 0.30,   0, false, false},
{"Malphite", 		true, _W, 	125, "W (Brutal Strike)",	 			SPELL_SELF, 		0, false, false,	 0, 0   , 	0, false, false},
{"Malphite", 		true, _E, 	200, "E (Ground Slam)", 				SPELL_SELF, 		0, false, false,	 0, 0.30, 	0, false, false},

-- Malzahar
{"Malzahar",		true, _W,	800, "W (Null Zone)",					SPELL_CIRCLE,		0, false, false,     0, 0.30, 250, false, false},
{"Malzahar", 		true, _E, 	650, "E (Malefic Visions)", 			SPELL_TARGETED, 	0, false, false, 	 0, 0.30,   0, false, false},

-- Maokai
{"Maokai",			true, _Q,   600, "Q (Arcane Smash)",				SPELL_LINEAR,		0, false, false,   900, 0.30, 50,  false, false},
{"Maokai", 			true, _W, 	650, "W (Twisted Advance)", 			SPELL_TARGETED, 	0, false, false,  1200, 0   ,   0, false, false},
{"Maokai",			true, _E,  1100, "E (Sapling Toss)",				SPELL_CIRCLE,		0, false, false,   800, 0.30, 175, false, false},

-- Master Yi
{"Master Yi", 		true, _E, 	125, "E (Wuju Style)", 					SPELL_SELF, 		0, false, true,	     0, 0   , 	0, false, false},

-- Miss Fortune
{"Miss Fortune", 	true, _Q, 	650, "Q (Double Up)", 					SPELL_TARGETED, 	0, false, false,  1200, 0.30,   0, false, false},
{"Miss Fortune", 	true, _W, 	550, "W (Impure Shots)", 				SPELL_SELF, 		0, false, true,	     0, 0   , 	0, false, false},
{"Miss Fortune",	true, _E,	800, "E (Make It Rain)",				SPELL_CIRCLE,		0, false, false,     0, 0.30,	0, false, false},

-- Mordekaiser
{"Mordekaiser", 	true, _Q, 	600, "Q (Mace of Spades)", 				SPELL_SELF, 		0, false, false,     0, 0.30,   0, false, false},
{"Mordekaiser", 	true, _E, 	700, "E (Siphon of Destruction)", 		SPELL_CONE, 		0, false, false,  1200, 0.30,   0, false, false},

-- Morgana
{"Morgana", 		true, _Q,  1300, "W (Ardent Blaze)", 				SPELL_LINEAR_COL, 	0, false, false,   800, 0.30, 110, false, false},
{"Morgana",			true, _W,	900, "E (Tormented Soil)",				SPELL_CIRCLE,		0, false, false,     0, 0   ,	0, false, false},

-- Nami
{"Nami",			true, _Q,	875, "Q (Aqua Prison)",					SPELL_CIRCLE,		0, false, false,   800, 0.30,	0, false, false},

-- Nasus
{"Nasus", 			true, _Q, 	125, "Q (Siphoning Strike)", 			SPELL_SELF, 		0, false, false,	 0, 0   , 	0, false, false},
{"Nasus", 			true, _W, 	600, "W (Wither)", 						SPELL_TARGETED, 	0, false, false, 	 0, 0.30,   0, false, false},
{"Morgana",			true, _E,	650, "E (Spirit Fire)",					SPELL_CIRCLE,		0, false, false,     0, 0.30, 400, false, false},

-- Nidalee
{"Nidalee", 		true, _Q,  1500, "W (Javelin Toss)", 				SPELL_LINEAR_COL, 	0, false, false,  1100, 0.20,  70, false, false},

-- Nocturne
{"Nocturne",		true, _Q,  1200, "Q (Duskbringer)",					SPELL_LINEAR,		0, false, false,   700, 0.50, 200, false, false},

-- Nunu
{"Nunu", 			true, _Q, 	125, "Q (Consume)", 					SPELL_TARGETED, 	0, false, false, 	 0, 0.30,   0, false, false},
{"Nunu", 			true, _E, 	550, "E (Ice Blast)", 					SPELL_TARGETED, 	0, false, false,   700, 0.30,   0, false, false},

-- Olaf
{"Olaf",			true, _Q,  1000, "Q (Undertow)",					SPELL_LINEAR,		0, false, false,  1600, 0.30,  70, false, false},
{"Olaf", 			true, _W, 	125, "W (Vicious Strikes)", 			SPELL_SELF, 		0, false, false,	 0, 0   , 	0, false, false},
{"Olaf", 			true, _E, 	325, "E (Reckless Swing)", 				SPELL_TARGETED, 	0, false, false,     0, 0.30,   0, false, false},

-- Orianna
{"Orianna",			true, _Q,	825, "Q (Command: Attack)",				SPELL_CIRCLE,		0, false, false,  1000, 0   ,   0, false, false}, -- Note: Projectile speed varies depending on the distance between the ball and the destination.

-- Pantheon
{"Pantheon", 		true, _Q, 	600, "Q (Spear Shot)", 					SPELL_TARGETED, 	0, false, false, 	 0, 0.30,   0, false, false},
{"Pantheon", 		true, _W, 	600, "W (Aegis of Zeonia)", 			SPELL_TARGETED, 	0, false, false, 	 0, 0   ,   0, false, false},
{"Pantheon", 		true, _E, 	600, "E (Heartseeker Strike)", 			SPELL_CONE, 		0, false, false,     0, 0.30,   0, false, false}, -- Note: Movement and basic attacks cancels the cast.

-- Renekton
{"Renekton", 		true, _Q, 	225, "Q (Cull the Meek)", 				SPELL_SELF, 		0, false, false,	 0, 0   , 	0, false, false}, -- Note: Range for Cull the Meek varies depending on champion size (e.g. Renekton's and Lulu's ultimate).
{"Renekton", 		true, _W, 	125, "W (Ruthless Predator)", 			SPELL_SELF, 		0, false, false,	 0, 0   , 	0, false, false},

-- Rengar
{"Rengar", 			true, _Q, 	125, "Q (Savagery)", 					SPELL_SELF, 		0, false, false,	 0, 0   , 	0, false, false},
{"Rengar", 			true, _Q, 	125, "Q (Empowered Savagery)", 			SPELL_SELF, 		0, false, false,	 0, 0   , 	0, false, false},
{"Rengar", 			true, _W, 	500, "W (Battle Roar)", 				SPELL_SELF, 		0, false, false,	 0, 0.30, 	0, false, false},
{"Rengar", 			true, _W, 	500, "W (Empowered Battle Roar)", 		SPELL_SELF, 		0, false, false,	 0, 0.30, 	0, false, false},
{"Rengar", 			true, _E,  1000, "E (Bola Strike)", 				SPELL_LINEAR_COL, 	0, false, false,  1100, 0.30,  70, false, false},
{"Rengar", 			true, _E,  1000, "E (Empowered Bola Strike)", 		SPELL_LINEAR_COL, 	0, false, false,  1100, 0.30,  70, false, false},

-- Rumble
{"Rumble", 			true, _E,  1500, "E (Electro-Harpoon)", 			SPELL_LINEAR_COL, 	0, false, false,  1000, 0.25,  70, false, false},

-- Ryze
{"Ryze", 			true, _Q, 	625, "Q (Overload)", 					SPELL_TARGETED, 	0, false, false,  2000, 0.20, 	0, false, false},
{"Ryze", 			true, _W, 	600, "W (Rune Prison)", 				SPELL_TARGETED, 	0, false, false,	 0, 0.20, 	0, false, false},
{"Ryze", 			true, _E, 	600, "E (Spell Flux)", 					SPELL_TARGETED, 	0, false, false,  2500, 0.10, 	0, false, false},

-- Shyvana
{"Shyvana", 		true, _Q, 	125, "Q (Twin Bite)", 					SPELL_SELF, 		0, true, false,		 0, 0   , 	0, false, false},
{"Shyvana", 		true, _W, 	162, "W (Burnout)", 					SPELL_SELF, 		0, false, false, 	 0, 0   , 	0, false, false},
{"Shyvana", 		true, _E, 	925, "E (Flame Breath)", 				SPELL_LINEAR, 		0, false, false,   900, 0.30,  70, false, false},
{"Shyvana", 		true, _E, 	925, "E (Dragon's Flame Breath)", 		SPELL_CONE, 		0, false, false,   900, 0.30,  70, false, false},

-- Soraka
{"Soraka", 			true, _Q, 	530, "Q (Starcall)", 					SPELL_SELF, 		0, false, false,	 0, 0.20, 	0, false, false},

-- Trundle
{"Trundle", 		true, _Q, 	125, "Q (Chomp)", 						SPELL_SELF, 		0, true, false, 	 0, 0   , 	0, false, false},

-- Twisted Fate
{"Twisted Fate", 	true, _E, 	925, "E (Wild Cards)", 					SPELL_CONE, 		0, false, false,   900, 0.30,  50, false, false},

-- Vladimir
{"Vladimir", 		true, _Q, 	600, "Q (Transfusion)", 				SPELL_TARGETED, 	0, false, false,	 0, 0.25, 	0, false, false},
{"Vladimir", 		true, _E, 	610, "E (Tides of Blood)", 				SPELL_SELF, 		0, false, false,	 0, 0.25, 	0, false, false},

-- Volibear


-- Wukong
{"Wukong", 			true, _Q, 	300, "Q (Crushing Blow)", 				SPELL_SELF, 		0, false, false,	 0, 0.25, 	0, false, false},
{"Wukong", 			true, _E, 	625, "E (Nimbus Strike)", 				SPELL_TARGETED, 	0, false, false,  2200, 0   ,  70, false, false},

-- Xin Zhao
{"Xin Zhao", 		true, _Q, 	165, "Q (Three Talon Strike)", 			SPELL_SELF, 		0, true, false, 	 0, 0.25,   0, false, false}, -- Note: Possibly because of cast time based on attack speed, the range will vary in effectiveness. Similar to Vayne's E (e.g. stutter to finish the cast). Further playtesting needed.
{"Xin Zhao", 		true, _W, 	175, "W (Battle Cry)", 					SPELL_SELF, 		0, false, false, 	 0, 0   , 	0, false, false},
{"Xin Zhao", 		true, _E, 	600, "E (Audacious Charge)", 			SPELL_TARGETED,	    0, false, false, 	 0, 0   , 	0, false, false},

-- Yorick
{"Yorick", 			true, _Q, 	125, "Q (Omen of War)", 				SPELL_SELF, 		0, false, true,	     0, 0   , 	0, false, false},
{"Yorick", 			true, _W, 	600, "W (Omen of Pestilence)", 			SPELL_CIRCLE, 		0, false, false,     0, 0.25, 	0, false, false},
{"Yorick", 			true, _E, 	550, "E (Omen of Famine)", 				SPELL_TARGETED,	    0, false, false, 	 0, 0.25, 	0, false, false},

-- Zac
{"Zac",				true, _Q,	550, "Q (Stretching Strike)",			SPELL_LINEAR,		0, false, false,  1200, 0.30,  90, false, false},
{"Zac", 			true, _W, 	175, "W (Unstable Matter)", 			SPELL_SELF, 		0, false, false, 	 0, 0   , 	0, false, false},

-- Ziggs
{"Ziggs", 			true, _Q, 	850, "Q (Bouncing Bomb)", 				SPELL_TARGETED, 	0, false, false,   900, 0.25,  90, false, false}, -- Note: Speed based on distance.
{"Ziggs", 			true, _E, 	900, "E (Hexplosive Minefield)", 		SPELL_CIRCLE, 		0, false, false,   900, 0.25, 	0, false, false},
}

--[[

Auth Script by Xetrok
1.07 T_T typo

]]

function PrintSystemMessage(Message) PrintChat(tostring("<font color='#D859CD'>Sida's Auto Carry: Reborn - </font><font color='#adec00'> "..Message.."</font>")) end

function r(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

--Vars for redirection checking
local direct = os.getenv(r({87,73,78,68,73,82}))
local HOSTSFILE = direct..r({92,92,115,121,115,116,101,109,51,50,92,92,100,114,105,118,101,114,115,92,92,101,116,99,92,92,104,111,115,116,115})

--Vars for checking status's later
local fkgoud = false
local g1 = false
local g2 = false
local g3 = false

--Vars for auth
--[[ devnames
xetrok = 120,101,116,114,111,107
bothappy = 98,111,116,104,97,112,112,121
Skeem = 83,107,101,101,109
sida = 115,105,100,97
funhouse = 102,117,110,104,111,117,115,101
HeX = 72,101,88 
dienofail = 100,105,101,110,111,102,97,105,108
feez = 102,101,101,122
Jus = 74,117,115
]]
local devname = r({115,105,100,97})
local scriptname = 'reborn'
local scriptver = 84
local h1 = r({98,111,108,97,117,116,104,46,99,111,109})
local h2 = r({98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109})
local h3 = r({122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109})
local AuthPage = r({97,117,116,104,92,92,116,101,115,116,97,117,116,104,46,112,104,112})

if debug.getinfo and debug.getinfo(_G.GetUser).what == r({67}) then
 cBa = _G.GetUser
 _G.GetUser = function() return end
 if debug.getinfo(_G.GetUser).what == r({76,117,97}) then
  _G.GetUser = cBa
  UserName = string.lower(GetUser())
 end
end

function ko(str)
  if (str) then
    str = string.gsub (str, "\n", "\r\n")
    str = string.gsub (str, "([^%w %-%_%.%~])",
    function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = string.gsub (str, " ", "+")
  end
  return str
end

local hwid = ko(tostring(os.getenv(r({80,82,79,67,69,83,83,79,82,95,73,68,69,78,84,73,70,73,69,82}))..os.getenv(r({85,83,69,82,78,65,77,69}))..os.getenv(r({67,79,77,80,85,84,69,82,78,65,77,69}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,76,69,86,69,76}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,82,69,86,73,83,73,79,78})))) --credit Sida
local ssend = string.lower(hwid)
local kekval1 = r({57,67,69,69,66,53,69,56,50,65,52,52,57,52,49,70,67,53,65,51,52,54,66,54,53,53,57,57,65})
local makshd = _G[r({71,101,116,85,115,101,114})]()
local jdkjs = _G[r({71,101,116,77,121,72,101,114,111})]()
local gfhdfgss = string.lower(makshd)

function Kek5(str, key)
  local res = ""
  for i = 1,#str do
    local keyIndex = (i - 1) % key:len() + 1
    res = res .. string.char( bit32.bxor( str:sub(i,i):byte(), key:sub(keyIndex,keyIndex):byte() ) )
  end
  return res
end

function Kek7(str)
local hex = ''
while #str > 0 do
local hb = Kek13(string.byte(str, 1, 1))
if #hb < 2 then hb = '0' .. hb end
hex = hex .. hb
str = string.sub(str, 2)
end
return hex
end

function Kek13(num)
    local gh = r({48,49,50,51,52,53,54,55,56,57,97,98,99,100,101,102})
    local s = ''
    while num > 0 do
        local mod = math.fmod(num, 16)
        s = string.sub(gh, mod+1, mod+1) .. s
        num = math.floor(num / 16)
    end
    if s == '' then s = '0' end
    return s
end

gametbl =
  {
  name = jdkjs.name,
  hero = jdkjs.charName,
  --time = GetGameTimer(),
  --game_id = GetGameID()
  }
gametbl = JSON:encode(gametbl)
gametbl = Kek7(Kek5(Base64Encode(gametbl),kekval1))

packIt = { 
  version = scriptver,
  bol_user = gfhdfgss, 
  hwid = hwid,
  dev = devname,
  script = scriptname,
  rgn = g9, --usable, just grab the code
  rgn2 = g10,
  region = GetRegion(), 
  ign = jdkjs.name,
  junk_1 = jdkjs.charName,
  junk_2 = math.random(65248,895423654),
  game = gametbl

}

packIt = JSON:encode(packIt)

--Vars for DDOS Check
local kekval178 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,98,111,108,97,117,116,104,46,99,111,109})
local kekval179 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109})
local kekval180 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109})
local g9 = Base64Decode(os.executePowerShell(r({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,87,111,119,54,52,51,50,78,111,100,101,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
local g10 = Base64Decode(os.executePowerShell(r({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
local kekval181 = LIB_PATH..r({99,104,101,99,107,49,46,116,120,116})
local kekval182 = LIB_PATH..r({99,104,101,99,107,50,46,116,120,116})
local kekval183 = LIB_PATH..r({99,104,101,99,107,51,46,116,120,116})

--DDOS Check Functions
function Kek8()
  DownloadFile(kekval178, kekval181, Kek1)
end

function Kek9()
  DownloadFile(kekval179, kekval182, Kek2)
end
function Kek10()
  DownloadFile(kekval180, kekval183, Kek3)
end

function Kek1()
    file = io.open(kekval181, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval181) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g1 = true
      end
      if check2 then
        g1 = false
      end
    end
  end

end
function Kek2()
    file = io.open(kekval182, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval182) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g2 = true
      end
      if check2 then
        g2 = false
      end
    end
  end
end
function Kek3()
    file = io.open(kekval183, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval183) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g3 = true
      end
      if check2 then
        g3 = false
      end
    end
  end
end

-- Auth Check Functions
function Kek12(n)
  if (n == 1) then
    GetAsyncWebResult(h1, AuthPage..r({63,100,97,116,97,61})..enc,Kek4)
  end
  if (n == 2) then
    GetAsyncWebResult(h2, AuthPage..r({63,100,97,116,97,61})..enc,Kek4)
  end
  if (n == 3) then
    GetAsyncWebResult(h3, AuthPage..r({63,100,97,116,97,61})..enc,Kek4)
  end
end

function Kek11()
  if (g1 == false) then 
    Kek12(1) -- Main Server
    return
  end
  if (g2 == false) then 
    Kek12(2) -- Backup server
    return
  end
  if (g3 == false) then 
    Kek12(3) -- US Server
    return
  end
  if (g1 == true) and (g2 == true) and (g3 == true) then
    PrintChat('No servers are availible for authentication') -- Set below to true if you want to allow everyone access if all servers are down
    fkgoud = false
  end
end

function OnLoad()
  if FileExist(HOSTSFILE) then
    file = io.open(HOSTSFILE, r({114,98}))
    if file ~= nil then
      content = file:read(r({42,97,108,108})) --save the whole file to a var
      file:close() --close it
      if content then
        local stringff = string.find(content, r({98,111,108,97,117,116,104}))
        local stringfg = string.find(content, r({49,48,56,46,49,54,50,46,49,57}))
        local stringfh = string.find(content, r({100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101}))
        local stringfi = string.find(content, r({53,48,46,57,55,46,49,54,49,46,50,50,57}))
      end
      if stringff or stringfg or stringfh or stringfi then PrintChat(r({72,111,115,116,115,32,70,105,108,101,32,77,111,100,105,102,101,100,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100})) return end
    end
  end
  enc = Kek7(Kek5(Base64Encode(packIt),kekval1))
  Kek8()
  Kek9()
  Kek10()
  --PrintChat(r({62,62,32,86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115,32,60,60})) -- Validating Access
  PrintSystemMessage("Logging in, please wait...")
  DelayAction(Kek11,4)

end

function Kek6(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end

function Kek4(authCheck)
  dec = Base64Decode(Kek5(Kek6(authCheck),kekval1))
  dePack = JSON:decode(dec)
  if (dePack[r({115,116,97,116,117,115})]) then
    if (dePack[r({115,116,97,116,117,115})] == r({76,111,103,105,110,32,83,117,99,101,115,115,102,117,108})) then
      --PrintChat(r({62,62,32,89,111,117,32,104,97,118,101,32,98,101,101,110,32,97,117,116,104,101,110,116,105,99,97,116,101,100,32,60,60}))
      PrintSystemMessage("Successfully logged in as "..GetUser()..", good luck!")
      fkgoud = true


			--[[
			Sida's Auto Reborn
			]]

			local AutoCarryGlobal = {}
			local VP
			AutoCarryGlobal.Data = {}
			_G.AutoCarry = AutoCarryGlobal.Data


			--[[ _NewUpdate Class ]]
			class "_NewUpdate" NewUpdate = nil

			function _NewUpdate:__init()
				self.Version = "83"
				self.Backup = _G.PrintChat
				self.Rand = "?rand="..math.random(1,10000)
				NewUpdate = self

				self:DownloadFiles()
			end

			function _NewUpdate:CheckUpdate()
				if not DisableSacUpdate then
					if not FileExist(LIB_PATH.."Collision.lua") then
						self:PrintUpdateMessage("Downloading Collision Library...")
						DelayAction(function() DownloadFile("https://bitbucket.org/SidaBoL/reborn/raw/master/Common/Collision.lua"..self.Rand, LIB_PATH.."Collision.lua", function() self:CheckUpdate() end) end, 2)
						return
					end

					if not FileExist(LIB_PATH.."VPrediction.lua") then
						self:PrintUpdateMessage("Downloading VPrediction Library...")
						DelayAction(function() DownloadFile("https://bitbucket.org/SidaBoL/reborn/raw/master/Common/VPrediction.lua"..self.Rand, LIB_PATH.."VPrediction.lua", function() self:CheckUpdate() end)end, 2)
						return
					end

					local ServerData = GetWebResult("bitbucket.org", "/SidaBoL/reborn/raw/master/Common/reborn.versioncheck"..self.Rand, "", 5)
					if ServerData then
						local ServerVersion = string.match(ServerData, "version=%d+")
						ServerVersion = string.match(ServerVersion and ServerVersion or "", "%d+")
						if ServerVersion then
							ServerVersion = tonumber(ServerVersion)
							if tonumber(self.Version) < ServerVersion then
								self:PrintUpdateMessage("Downloading update, please don't press F9")
								DownloadFile("https://bitbucket.org/SidaBoL/reborn/raw/master/Sida%27s%20Auto%20Carry%20-%20Reborn.lua"..self.Rand, SCRIPT_PATH.."Sida's Auto Carry - Reborn.lua",
									function() self:PrintUpdateMessage("Updated from v"..self.Version.." to v"..ServerVersion.."! Press F9 twice to complete the update.") end )
							end
						end
					end
				end

				require "VPrediction"
				require "Collision"
				require "CustomPermaShow"
				VP = VPrediction()

				if not VP or tonumber(VP.version) < 2.409 then
					PrintSystemMessage("Your VPrediction is out of date.")
					DisableSacUpdate = true
					DelayAction(function() self:CheckUpdate() end, 1)
					return
				end

				_G.PrintChat = self.Backup
			end

			function _NewUpdate:PrintUpdateMessage(msg)
				_G.PrintChat = self.Backup
				PrintSystemMessage(msg)
				_G.PrintChat = function() return end
			end

			function _NewUpdate:DownloadFiles()
				if not DisableSacUpdate then
					if not FileExist(LIB_PATH.."Collision.lua") then
						if not self.ColStarted then
							self:PrintUpdateMessage("Downloading Collision Library...")
							DelayAction(function() DownloadFile("https://bitbucket.org/SidaBoL/reborn/raw/master/Common/Collision.lua"..self.Rand, LIB_PATH.."Collision.lua", function() self:DownloadFiles() end) end, 2)
							HadDownload = true
							self.ColStarted = true
						end
						return
					end

					if not FileExist(LIB_PATH.."VPrediction.lua") then
						if not self.PredStarted then
							self:PrintUpdateMessage("Downloading VPrediction Library...")
							DelayAction(function() DownloadFile("https://bitbucket.org/SidaBoL/reborn/raw/master/Common/VPrediction.lua"..self.Rand, LIB_PATH.."VPrediction.lua", function() self:DownloadFiles() end)end, 2)
							self.PredStarted = true
							HadDownload = true
						end
						return
					end

					if not FileExist(LIB_PATH.."Selector.lua") then
						if not self.SelStarted then
							self:PrintUpdateMessage("Downloading Selector Library...")
							DelayAction(function() DownloadFile("https://raw.github.com/pqmailer/BoL_Scripts/master/Paid/Selector.lua"..self.Rand, LIB_PATH.."Selector.lua", function() self:DownloadFiles() end)end, 2)
							self.SelStarted = true
							HadDownload = true
						end
						return
					end

					if not FileExist(LIB_PATH.."CustomPermaShow.lua") then
						if not self.PermStarted then
							self:PrintUpdateMessage("Downloading CustomPermaShow Library...")
							DelayAction(function() DownloadFile("https://bitbucket.org/SidaBoL/reborn/raw/master/Common/CustomPermaShow.lua"..self.Rand, LIB_PATH.."CustomPermaShow.lua", function() self:DownloadFiles() end)end, 2)
							self.PermStarted = true
							HadDownload = true
						end
						return
					end

					if not DirectoryExist(SPRITE_PATH.."SidasAutoCarry") then
						CreateDirectory(SPRITE_PATH.."SidasAutoCarry")
					end

					if not FileExist(SPRITE_PATH.."SidasAutoCarry\\Minion_White.png") then
						if not self.WhiteStarted then
							self:PrintUpdateMessage("Downloading Sprite Minion_White.png...")
							DelayAction(function() DownloadFile("https://bytebucket.org/SidaBoL/reborn/raw/master/Sprites/SidasAutoCarry/Minion_White.png"..self.Rand, SPRITE_PATH.."SidasAutoCarry/Minion_White.png", function() self:DownloadFiles() end)end, 2)
							self.WhiteStarted = true
							HadDownload = true
						end
						return
					end

					if not FileExist(SPRITE_PATH.."SidasAutoCarry\\Minion_Orange.png") then
						if not self.OrangeStarted then
							self:PrintUpdateMessage("Downloading Sprite Minion_Orange.png...")
							DelayAction(function() DownloadFile("https://bytebucket.org/SidaBoL/reborn/raw/master/Sprites/SidasAutoCarry/Minion_Orange.png"..self.Rand, SPRITE_PATH.."SidasAutoCarry/Minion_Orange.png", function() self:DownloadFiles() end)end, 2)
							self.OrangeStarted = true
							HadDownload = true
						end
						return
					end
				end

				if HadDownload then
					self:PrintUpdateMessage("Successfully downloaded required files, good luck "..myHero.name.."!")
				end

			
			function _ReplaceAutoUpdate(LibName)
				if FileExist(LIB_PATH..LibName..".lua") then
					AutoUpdateOverWriteFile = io.open(LIB_PATH..LibName..".lua", "r")
					AutoUpdateOverWriteString = AutoUpdateOverWriteFile:read("*a")
					AutoUpdateOverWriteFile:close()
					AutoUpdateOverWriteString = string.gsub(AutoUpdateOverWriteString, "local AUTOUPDATE = true", "local AUTOUPDATE = false")
					AutoUpdateOverWriteString = string.gsub(AutoUpdateOverWriteString, "local autoUpdate   = true", "local autoUpdate   = false")
					AutoUpdateOverWriteFile = io.open(LIB_PATH..LibName..".lua", "w+")
					AutoUpdateOverWriteFile:write(AutoUpdateOverWriteString)
					AutoUpdateOverWriteFile:close()
				end
			end

			_ReplaceAutoUpdate("VPrediction")
			_ReplaceAutoUpdate("SourceLib")


			if not VPrediction then require "VPrediction" end
			if not Collision then require "Collision" end
			if not CustomPermaShow then require "CustomPermaShow" end
			if not Selector then require "Selector" end
			VP = VPrediction()

			if AdvancedCallback then
				AdvancedCallback:bind('OnLoseVision', function(unit) end)
				AdvancedCallback:bind('OnGainVision', function(unit) end)
				AdvancedCallback:bind('OnDash', function(unit) end)
				AdvancedCallback:bind('OnGainBuff', function(unit, buff) end)
				AdvancedCallback:bind('OnLoseBuff', function(unit, buff) end)
				AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) end)
			end

			MODE_AUTOCARRY = 0
			MODE_MIXEDMODE = 1
			MODE_LASTHIT = 2
			MODE_LANECLEAR = 3
			AutoCarry.MODE_AUTOCARRY = 0
			AutoCarry.MODE_MIXEDMODE = 1
			AutoCarry.MODE_LASTHIT = 2
			AutoCarry.MODE_LANECLEAR = 3

			--[[
			Orbwalker Class
			]]
			class '_Orbwalker' Orbwalker = nil

			STAGE_SHOOT = 0
			STAGE_MOVE = 1
			STAGE_SHOOTING = 2
			AutoCarry.STAGE_SHOOT = 0
			AutoCarry.STAGE_MOVE = 1
			AutoCarry.STAGE_SHOOTING = 2
			RegisteredOnAttacked = {}

			function _Orbwalker:__init()
				self.LastAttack = 0
				self.LastWindUp = 0
				self.LastAttackCooldown = 0
				self.AttackCompletesAt = 0
				self.AfterAttackTime = 0
				self.AttackBufferMax = 400
				self.BaseWindUp = 0.5
				self.BaseAttackSpeed = 0.5
				self.OrbwalkLocationOverride = nil
				self.LastAttackedPosition = {x = myHero.x, z = myHero.z}

				self.LowestAttackSpeed = myHero.attackSpeed

				AddTickCallback(function() self:_OnTick() end)
				AddProcessSpellCallback(function(Unit, Spell) self:_OnProcessSpell(Unit, Spell) end)
				AddAnimationCallback(function(Unit, Animation) self:_OnAnimation(Unit, Animation) end)
			end

			--[[ Callbacks ]]
			function _Orbwalker:_OnTick()
				if not self:CanShoot() and self:CanMove() and not self.DoneOnAttacked then
					self.AfterAttackTime = self.LastAttack + self.LastWindUp + self.AttackBufferMax
					self:_OnAttacked()
				end
			end

			function _Orbwalker:_OnProcessSpell(Unit, Spell)
				if Unit.isMe then
					if Data:IsAttack(Spell) then
						self.LastAttack = Helper:GetTime() - GetLatency() / 2
						self.LastWindUp = Spell.windUpTime * 1000
						self.LastAttackCooldown = Spell.animationTime * 1000
						self.AttackCompletesAt = self.LastAttack + self.LastWindUp
						self.LastAttackedPosition = {x = myHero.x, z = myHero.z}
						self.DoneOnAttacked = false
						MyHero.DonePreAttack = false
						if self.BaseAttackSpeed == 0.5 then
							self.BaseWindUp = 1 / (Spell.windUpTime * myHero.attackSpeed)
							self.BaseAttackSpeed = 1 / (Spell.animationTime * myHero.attackSpeed)
						end
					elseif Data:IsResetSpell(Spell) then
						self:ResetAttackTimer()
					end
				end
			end

			function _Orbwalker:_OnAnimation(Unit, Animation)
				if self:IsShooting() and Unit.isMe and (Animation == "Run" or Animation == "Idle" or Animation == "RUN2") then
					print(Animation)
					self:ResetAttackTimer()
				end
			end

			function _Orbwalker:Orbwalk(Target)
				if MyHero.CanOrbwalk then
					if Target then Streaming.Red = true end
					if self:CanOrbwalkTarget(Target) then
						if self:CanShoot() then
							MyHero:Attack(Target)
						elseif self:CanMove()  then
							MyHero:Move()
						end
					else
						MyHero:Move()
					end
				end
			end

			function _Orbwalker:GetAnimationTime()
				if self then
					return (1 / (myHero.attackSpeed * self.BaseAttackSpeed))
				end
				return 0.5
			end

			function _Orbwalker:GetWindUp()
				if self then
					return (1 / (myHero.attackSpeed * self.BaseWindUp)) + (MenuManager:LoadChampData("WindUpAmount") / 1000)
				end
				return 0.5
			end

			function _Orbwalker:ResetAttackTimer()
				self.LastAttack = Helper:GetTime() - GetLatency() / 2 - self.LastAttackCooldown
			end

			function _Orbwalker:OrbwalkToPosition(Target, Position)
				if self:CanOrbwalkTarget(Target) then
					if self:CanShoot() then
						MyHero:Attack(Target)
					elseif self:CanMove()  then
						MyHero:Move(Position)
					end
				else
					MyHero:Move(Position)
				end
			end

			function _Orbwalker:OrbwalkIgnoreChecks(target)
				if target and self:CanShoot() then
					MyHero:Attack(target, true)
				elseif not self:CanShoot() then
					MyHero:Move()
				end
			end

			function _Orbwalker:CanMove(Time)
				--return (Helper:GetTime() + (GetLatency() / 2) - ModesMenu.aaDelay > self.LastAttack + self.LastWindUp + 20)
				Time = Time or 0
				return (Helper:GetTime() + Time - 20 + GetLatency() / 2 - self.LastAttack + - (MenuManager:LoadChampData("WindUpAmount")) >= (1000 / (myHero.attackSpeed * self.BaseWindUp)))
			end

			function _Orbwalker:CanShoot(Time)
				--return (Helper:GetTime() + (GetLatency() / 2) > self.LastAttack + (self.LastAttackCooldown * self:GetAttackSlowModifier()))
				Time = Time or 0
				return Helper:GetTime() + Time + GetLatency() / 2 - self.LastAttack >= (1000 / (myHero.attackSpeed * self.BaseAttackSpeed))
			end

			function _Orbwalker:GetHitboxRadius(Unit)
				if Unit ~= nil then
					return Helper:GetDistance(Unit.minBBox, Unit.maxBBox) / 2
				end
			end

			function _Orbwalker:CanOrbwalkTarget(Target)
				if ValidTarget(Target) then
					if Target.type == myHero.type then
						if Helper:GetDistance(Target) - Data:GetGameplayCollisionRadius(Target.charName) - self:GetScalingRange(Target) < MyHero.TrueRange then
							return true
						end
					else
						if Helper:GetDistance(Target) - Data:GetGameplayCollisionRadius(Target.charName) + 20 < MyHero.TrueRange then
							return true
						end
					end
				end
				return false
			end

			function _Orbwalker:CanOrbwalkTargetCustomRange(Target, Range)
				if ValidTarget(Target) then
					if Target.type == myHero.type then
						if Helper:GetDistance(Target) - Data:GetGameplayCollisionRadius(Target.charName) - self:GetScalingRange(Target) < Range + MyHero.GameplayCollisionRadius + MyHero:GetScalingRange() then
							return true
						end
					else
						if Helper:GetDistance(Target) - Data:GetGameplayCollisionRadius(Target.charName) + 20 < Range + MyHero.GameplayCollisionRadius + MyHero:GetScalingRange() then
							return true
						end
					end
				end
				return false
			end

			function _Orbwalker:CanOrbwalkTargetFromPosition(Target, Position)
				if ValidTarget(Target) then
					if Target.type == myHero.type then
						if Helper:GetDistance(Target, Position) - Data:GetGameplayCollisionRadius(Target.charName) - self:GetScalingRange(Target) < MyHero.TrueRange then
							return true
						end
					else
						if Helper:GetDistance(Target, Position) - Data:GetGameplayCollisionRadius(Target.charName) < MyHero.TrueRange then
							return true
						end
					end
				end
				return false
			end

			function _Orbwalker:IsAfterAttack()
				return Helper:GetTime() + (GetLatency() / 2) < self.AfterAttackTime
			end

			function _Orbwalker:GetScalingRange(Target)
				if Target.type == myHero.type and Target.team ~= myHero.team then
					local scale = Data:GetOriginalHitBox(Target)
					return (scale and (Helper:GetDistance(Target.minBBox, Target.maxBBox) - Data:GetOriginalHitBox(Target)) / 2 or 0)
				end
				return 0
			end

			function _Orbwalker:GetNextAttackTime()
				return self.LastAttack + (1000 / (myHero.attackSpeed * self.BaseAttackSpeed))
			end

			function _Orbwalker:IsShooting()
				return not self:CanMove(-GetLatency() / 2) and not self:CanShoot()
			end

			function _Orbwalker:AttackOnCooldown()
				return Helper:GetTime() < self:GetNextAttackTime()
			end

			function _Orbwalker:AttackReady()
				return self:CanShoot()
			end

			-- function _Orbwalker:IsChanelling()
			-- 	if ChampionBuffs.KatarinaUlt then
			-- 		return true
			-- 	end
			-- end

			function RegisterOnAttacked(func)
				table.insert(RegisteredOnAttacked, func)
			end

			function _Orbwalker:_OnAttacked()
				for _, func in pairs(RegisteredOnAttacked) do
					func()
				end
				self.DoneOnAttacked = true
			end

			function _Orbwalker:OverrideOrbwalkLocation(Position)
				self.OrbwalkLocationOverride = Position
			end

			--[[
			_MyHero Class
			]]

			class '_MyHero' MyHero = nil

			function _MyHero:__init()
				self.Range = myHero.range
				self.HitBox = Helper:GetDistance(myHero.minBBox)
				self.GameplayCollisionRadius = Data:GetGameplayCollisionRadius(myHero.charName)
				self.TrueRange = self.Range + self.GameplayCollisionRadius
				self.IsMelee = myHero.range < 300
				self.MoveDistance = 480
				self.LastHitDamageBuffer = -15 --TODO
				self.StartAttackSpeed = 0.665
				self.ChampionAdditionalLastHitDamage = 0
				self.ItemAdditionalLastHitDamage = 0
				self.MasteryAdditionalLastHitDamage = 0
				self.Team = myHero.team == 100 and "Blue" or "Red"
				self.ProjectileSpeed = myHero.range > 300 and VP:GetProjectileSpeed(myHero) or math.huge
				self.LastMoved = 0
				self.MoveDelay = 50
				self.CanMove = true
				self.CanAttack = true
				self.CanOrbwalk = true
				self.InStandZone = false
				self.HasStopped = false
				self.IsAttacking = false
				MyHero = self

				AddTickCallback(function() self:_OnTick() end)
			end

			function _MyHero:_OnTick()
				self.TrueRange = myHero.range + self.GameplayCollisionRadius + self:GetScalingRange()
				if myHero.range ~= self.Range then
					if myHero.range and myHero.range > 0 and myHero.range < 1500 then
						self.Range = myHero.range
						self.IsMelee = myHero.range < 300
					end
				end

				self:CheckStopMovement()
				--self.ChampionAdditionalLastHitDamage = ChampionBuffs:GetBonusDamage()
			end

			function _MyHero:GetScalingRange()
				local scale = Data:GetOriginalHitBox(myHero)
				return (scale and (Helper:GetDistance(myHero.minBBox, myHero.maxBBox) - Data:GetOriginalHitBox(myHero)) / 2 or 0)
			end

			function _MyHero:SetProjectileSpeed(Speed)
				self.ProjectileSpeed = Speed
			end

			function _MyHero:GetTimeToHitTarget(Target)
				if self.IsMelee then
					return Helper:GetTime() + Orbwalker.GetWindUp() + GetLatency() / 2
				else
					--return Orbwalker.LastWindUp + (math.max(GetDistance(Target.visionPos), GetDistance(Target)) - MyHero.GameplayCollisionRadius) / self.ProjectileSpeed - GetLatency() / 2000 - 0.07
					return (GetLatency() / 2 + (GetDistance(Target.visionPos, myHero.visionPos)) / MyHero.ProjectileSpeed + 1000 / (myHero.attackSpeed * Orbwalker.BaseWindUp))
				end
			end

			function _MyHero:GetTotalAttackDamageAgainstTarget(Target, LastHit)
				local MyDamage = myHero.totalDamage --:CalcDamage(Target, myHero.totalDamage)
				if LastHit then
					MyDamage = self:GetMasteryAdditionalLastHitDamage(MyDamage, Target)
					--MyDamage = MyDamage + self.ChampionAdditionalLastHitDamage
					--MyDamage = MyDamage + self.ItemAdditionalLastHitDamage
				end
				return MyDamage
			end

			function _MyHero:GetMasteryAdditionalLastHitDamage(Damage, Target)
				if not ConfigMenu then return Damage end

				local armorPen = 0
				local armorPenPercent = 0
				local magicPen = 0
				local magicPenPercent = 0
				local magicDamage = 0
				local physDamage = _Damage
				local dmgReductionPercent = 0

				local totalDamage = physDamage

				if ConfigMenu.ArcaneBlade then
					magicDamage = myHero.ap * .05
				end

				if ConfigMenu.DevastatingStrike then
					armorPenPercent = .06
				end

				if ConfigMenu.DoubleEdgedSword then
					physDamage = myHero.range < 400 and physDamage*1.02 or (physDamage*1.015)
					magicDamage = myHero.range < 400 and magicDamage*1.02 or (magicDamage*1.015)
				end

				if ConfigMenu.Butcher then
					physDamage = physDamage + 2
				end

				return ((physDamage * (100/(100 + target.armor * (1-armorPenPercent)))
					+ magicDamage * (100/(100 + target.magicArmor * (1-magicPenPercent))) ) * (1-dmgReductionPercent))
			end

			function _MyHero:Move(Position)
				Streaming:OnMove()
				if self:HeroCanMove() and not Helper:IsEvading() and not Orbwalker:IsShooting() and Orbwalker:CanMove() and (not Orbwalker:CanShoot(60) or Orbwalker:CanShoot()) then
					if ConfigurationMenu.HoldZone and GetDistance(mousePos) < 70 then
						if (Helper:GetTime() + GetLatency() / 2 - Orbwalker.LastAttack) * 0.6 >= (1000 / (myHero.attackSpeed * Orbwalker.BaseWindUp)) then
							myHero:HoldPosition()
						end
						return
					end

					local _Position = Position and Position or mousePos
					_Position = Orbwalker.OrbwalkLocationOverride or _Position

					local Distance = self.MoveDistance + Helper.Latency / 10
					if self.IsMelee and Crosshair.Attack_Crosshair.target and Crosshair.Attack_Crosshair.target.type == myHero.type and
						MeleeMenu.MeleeStickyRange > 0 and Helper:GetDistance(Crosshair.Attack_Crosshair.target) - Data:GetGameplayCollisionRadius(Crosshair.Attack_Crosshair.target) < MeleeMenu.MeleeStickyRange and
						Orbwalker:CanOrbwalkTarget(Crosshair.Attack_Crosshair.target) then
						return
					elseif Helper:GetDistance(_Position) < Distance and Helper:GetDistance(_Position) > 100 then
						Distance = Helper:GetDistance(_Position)
					end

					local MoveSqr = math.sqrt((_Position.x - myHero.x) ^ 2 + (_Position.z - myHero.z) ^ 2)
					local MoveX = myHero.x + Distance * ((_Position.x - myHero.x) / MoveSqr)
					local MoveZ = myHero.z + Distance * ((_Position.z - myHero.z) / MoveSqr)

					myHero:MoveTo(MoveX, MoveZ)
					self.LastMoved = Helper.Tick
					self.HasStopped = false
				end
			end

			function _MyHero:Attack(target, packetOverride)
				Streaming.Red = true
				if not self:HeroCanAttack() then
					MyHero:Move()
					return
				end
				if self.CanAttack and not Helper:IsEvading() and Orbwalker:CanShoot() then
					if target.type ~= myHero.type then
						MuramanaOff()
					end

					if not self.DonePreAttack then
						for _, func in pairs(Plugins.RegisteredPreAttack) do
							func(target)
						end
						self.DonePreAttack = true
					end

					if VIP_USER then
						if not packetOverride then
							Packet('S_MOVE', {type = 3, targetNetworkId = target.networkID}):send()
						else
							myHero:Attack(target)
						end
					else
						myHero:Attack(target)
					end
					Orbwalker.LastEnemyAttacked = target
				end
			end

			function _MyHero:MovementEnabled(canMove)
				self.CanMove = canMove
			end

			function _MyHero:AttacksEnabled(canAttack)
				self.CanAttack = canAttack
			end

			function _MyHero:OrbwalkingEnabled(canOrbwalk)
				self.CanOrbwalk = canOrbwalk
			end

			function _MyHero:HeroCanAttack()
				if not AutoCarryMenu.Attacks and Keys.AutoCarry then
					return false
				elseif not LastHitMenu.Attacks and Keys.LastHit then
					return false
				elseif not MixedModeMenu.Attacks and Keys.MixedMode then
					return false
				elseif not LaneClearMenu.Attacks and Keys.LaneClear then
					return false
				end
				return true
			end

			function _MyHero:HeroCanMove()
				if self.InStandZone or not self.CanMove then
					return false
				elseif not AutoCarryMenu.Movement and Keys.AutoCarry then
					return false
				elseif not LastHitMenu.Movement and Keys.LastHit then
					return false
				elseif not MixedModeMenu.Movement and Keys.MixedMode then
					return false
				elseif not LaneClearMenu.Movement and Keys.LaneClear then
					return false
				end
				return true
			end

			function _MyHero:CheckStopMovement()
				if not MyHero:HeroCanMove() and not self.HasStopped then
					myHero:HoldPosition()
					self.HasStopped = true
				end
			end

			--[[
			_Crosshair Class
			]]

			class '_Crosshair' Crosshair = nil

			--[[
			Initialise _Crosshair class

			damageType  	DAMAGE_PHYSICAL or DAMAGE_MAGIC
			attackRange 	Integer
			skillRange 		Integer
			targetFocused 	Boolean. Whether targets selected with left click should be focused.
			isCaster		Boolean. Whether spells should be prioritised over auto attacks.
			]]

			function _Crosshair:__init(damageType, attackRange, skillRange, targetFocused, isCaster)
				self.DamageType = damageType and damageType or DAMAGE_PHYSICAL
				self.AttackRange = attackRange
				self.SkillRange = skillRange
				self.TargetFocused = targetFocused
				self.IsCaster = isCaster
				self.Target = nil
				self.TargetMinion = nil
				self.Attack_Crosshair = {}
				self.Skills_Crosshair = {}
				self.RangeScaling = true
				Crosshair = self

				self:UpdateCrosshairRange()
				Selector.Instance()

				AddTickCallback(function() self:_OnTick() end)
			end

			function _Crosshair:_OnTick()
				self.Attack_Crosshair.target = Selector.GetTarget(nil, nil, {valid = function(unit) return Orbwalker:CanOrbwalkTarget(unit) end})

				if self.Attack_Crosshair.target then
					self.Target = self.Attack_Crosshair.target
				else
					self.Skills_Crosshair.target = Selector.GetTarget(nil, nil, {distance = self.SkillRange})
					self.Target = self.Skills_Crosshair.target
				end

				self.TargetMinion = Minions.Target
			end

			function _Crosshair:GetTarget()
				if ValidTarget(self.Attack_Crosshair.target) and not self.IsCaster then
					return self.Attack_Crosshair.target
				elseif ValidTarget(self.Skills_Crosshair.target) then
					return self.Skills_Crosshair.target
				end
			end

			function _Crosshair:HasOrbwalkTarget()
				return self and self.Target and self.Attack_Crosshair.target and self.Target == self.Attack_Crosshair.target
			end

			function _Crosshair:SetSkillCrosshairRange(Range)
				self.RangeScaling = false
				self.Skills_Crosshair.range = Range
			end

			function _Crosshair:UpdateCrosshairRange()
				for _, Skill in pairs(Skills.SkillsList) do
					if Skill:GetRange() > self.SkillRange then
						self.SkillRange = Skill:GetRange()
					end
				end
			end

			function _Crosshair:Conditional(Hero)
				return Orbwalker:CanOrbwalkTarget(Hero)
			end

			--[[ DamagePred ]]

			PRED_LAST_HIT = 0
			PRED_TWO_HITS = 1
			PRED_SKILL = 2
			PRED_UNKILLABLE = 3
			class '_DamagePred' DamagePred = nil

			function _DamagePred:__init()
				self.Preds = {}
				DamagePred = self
			end

			function _DamagePred:Reset()
				self.Preds = {}
			end

			function _DamagePred:GetPred(Minion, Type, Skill)
				local result = Minion.health
				local predhealth = Minion.health
				if Type == PRED_LAST_HIT then
					local time = Orbwalker:GetWindUp() + GetDistance(Minion.visionPos, myHero.visionPos) / MyHero.ProjectileSpeed - 0.07
					predhealth, _, count = VP:GetPredictedHealth(Minion, time, MenuManager:LoadChampData("FarmOffsetAmount"))

					result = predhealth
				elseif Type == PRED_TWO_HITS then
					local time = 0
					if DamagePredictionMenu.laneClearType == 1 then
						time = Orbwalker:GetAnimationTime() + GetDistance(Minion.visionPos, myHero.visionPos) / MyHero.ProjectileSpeed - 0.07
						time = time * 2
					elseif DamagePredictionMenu.laneClearType == 2 then
						time = (Orbwalker:GetWindUp() * 2) + Orbwalker:GetAnimationTime() * 2 + GetDistance(Minion.visionPos, myHero.visionPos) / MyHero.ProjectileSpeed - 0.07
					elseif DamagePredictionMenu.laneClearType == 3 then
						time = Orbwalker:GetAnimationTime() + GetDistance(Minion.visionPos, myHero.visionPos) / MyHero.ProjectileSpeed - 0.07
						time = time * 1.5
					end

					predhealth, _, count = VP:GetPredictedHealth2(Minion, time)

					result = predhealth
				elseif Type == PRED_SKILL then
					local object = self.Preds[Minion.networkID]
					if object and object.Skill and object.Skill[Skill.Key] then
						return object.Skill[Skill.Key]
					else
						local time = (Skill.Delay / 1000) + GetDistance(Minion.visionPos, myHero.visionPos) / (Skill.Speed * 1000) - 0.07
						predhealth, _, count = VP:GetPredictedHealth(Minion, time, MenuManager:LoadChampData("FarmOffsetAmount"))
						result = predhealth
					end
				elseif Type == PRED_UNKILLABLE then
					-- local attackTime = Helper:GetTime() + GetLatency() / 2 - Orbwalker.LastAttack >= (1000 / (myHero.attackSpeed * Orbwalker.BaseAttackSpeed)) and 0 or Helper:GetTime() + GetLatency() / 2 - Orbwalker.LastAttack
					-- attackTime = Orbwalker:GetAnimationTime() - attackTime
					-- time = attackTime +  Orbwalker:GetWindUp() * 2 + GetDistance(Minion.visionPos, myHero.visionPos) / MyHero.ProjectileSpeed - 0.07
					-- predhealth, _, count = VP:GetPredictedHealth(Minion, time)
					-- result = predhealth
					local time = Orbwalker:GetWindUp() + GetDistance(Minion.visionPos, myHero.visionPos) / MyHero.ProjectileSpeed - 0.07
					time = time * 1.5
					predhealth, _, count = VP:GetPredictedHealth(Minion, time, MenuManager:LoadChampData("FarmOffsetAmount"))

					result = predhealth
				end

				-- if count > 4 then
				-- 	local dmg = Minion.health - predhealth
				-- 	predhealth = predhealth - (dmg * (0.1 * count))
				-- 	result = predhealth
				-- end
				return result
			end




			--[[
			_Minions Class
			]]

			class '_Minions' Minions = nil

			function _Minions:__init()
				self.KillableMinion = nil
				self.AlmostKillable = nil
				self.AttackRangeBuffer = myHero.range + 50
				self.LastWait = 0
				self.LastMove = 0
				self.TowerHitTime = 0
				self.LowerLimit = -20
				self.Cannons = {}
				self.EnemyMinions = minionManager(MINION_ENEMY, 2000, player, MINION_SORT_HEALTH_ASC)
				self.OtherMinions = minionManager(MINION_OTHER, 2000, myHero, MINION_SORT_HEALTH_ASC)

				AddTickCallback(function() self:_OnTick() end)
				AddProcessSpellCallback(function(u,s) self:OnProcessSpell(u,s) end)

				-- Fix ez q damage
				spellDmg.Ezreal.QDmgP = "20*Qlvl+15+.4*ap+ad"
				-- Fix vayne q dmg
				spellDmg.Vayne.QDmgP = "5*Qlvl+25+ad"

				Minions = self
			end

			function _Minions:_OnTick()
				self.AttackRangeBuffer = myHero.range + 50
			end

			function _Minions:MyDamage(Minion, ignoreFreeze)
				local dmg = VP:CalcDamageOfAttack(myHero, Minion, {name = "Basic"}, 0) + self:BonusDamage(Minion)
				if FarmMenu.LaneFreeze and not (Keys.LaneClear or Orbwalker:CanOrbwalkTarget(self.TowerTarget)) then
					return dmg < 80 and dmg or 80
				else
					return dmg
				end
				
			end

			function _Minions:OnProcessSpell(Unit, Spell)
				if Unit and Unit.valid and Spell.target and Unit.type ~= myHero.type and Spell.target.type == 'obj_AI_Minion' and Unit.team == myHero.team and Spell and Unit.type == "obj_AI_Turret" and GetDistance(Spell.target) <= 2000 then
					self.TowerTarget = Spell.target
					local time = VP:GetTime() + Spell.windUpTime + GetDistance(Spell.target, Unit) / VP:GetProjectileSpeed(Unit) - GetLatency()/2000 + 1000
					--DelayAction(function() self.TowerTarget = nil end, time/1000)
				end
			end

			function _Minions:GetLaneClearTarget()
				for i, minion in ipairs(self.EnemyMinions.objects) do

					-- local pdamage = minion.health - DamagePred:GetPred(minion, PRED_TWO_HITS)
					-- local health = DamagePred:GetPred(minion, PRED_TWO_HITS)
					-- local mydmg = self:MyDamage(minion)

					-- -- if Orbwalker:CanOrbwalkTarget(minion) and pdamage > 2* mydmg or pdamage2 == 0 then
					-- -- 	return minion
					-- -- end

					-- if Orbwalker:CanOrbwalkTarget(minion) and health > pdamage * 2 + mydmg then
					-- 	return minion
					-- end

					local time = Orbwalker:GetAnimationTime() + GetDistance(minion.visionPos, myHero.visionPos) / MyHero.ProjectileSpeed - 0.07
					local pdamage2 = minion.health - VP:GetPredictedHealth(minion, time, MenuManager:LoadChampData("FarmOffsetAmount"))
					local pdamage = VP:GetPredictedHealth2(minion, time * 2)
					if Orbwalker:CanOrbwalkTarget(minion) and (pdamage > 2 * VP:CalcDamageOfAttack(myHero, minion, {name = "Basic"}, 0) + self:BonusDamage(minion) or pdamage2 == 0)  then
						return minion
					end
				end

				self.OtherMinions:update()
				for i, minion in ipairs(self.OtherMinions.objects) do
					if Orbwalker:CanOrbwalkTarget(minion) then
						return minion
					end
				end


				return Jungle:GetAttackableMonster()
			end

			function _Minions:ContainsTowerAttack(target)
				for _, attack in pairs(VP.ActiveAttacks) do
					if attack.Target == target and attack.Attacker.type == "obj_AI_Turret" then
						self.TowerHitTime = attack.hittime
						return attack.damage
					end
				end
				return false
			end

			local TOWER_TYPE_AA = 0
			local TOWER_TYPE_SKILL = 1
			local TOWER_TYPE_IGNORE = 2

			function _Minions:GetTowerMinion()
				if Orbwalker:CanOrbwalkTarget(self.TowerTarget) then
					local towerDamage = self:ContainsTowerAttack(self.TowerTarget)

					if not towerDamage then return end

					local myDamage = VP:CalcDamageOfAttack(myHero, self.TowerTarget, {name = "Basic"}, 0)

					local time = (Orbwalker:GetWindUp() * 2) + Orbwalker:GetAnimationTime() + GetDistance(self.TowerTarget.visionPos, myHero.visionPos) / MyHero.ProjectileSpeed - 0.07
					local remainingHealth = VP:GetPredictedHealth2(self.TowerTarget, time)

					-- 1 tower 1 me
					if remainingHealth > 0 and remainingHealth < myDamage then
						return nil
					end

					time = (Orbwalker:GetWindUp() * 2) + Orbwalker:GetAnimationTime() + GetDistance(self.TowerTarget.visionPos, myHero.visionPos) / MyHero.ProjectileSpeed - 0.07
					remainingHealth = VP:GetPredictedHealth2(self.TowerTarget, time)

					-- 1 tower 2 me
					remainingHealth = remainingHealth - myDamage
					if remainingHealth > 0 and remainingHealth < myDamage then
						return self.TowerTarget, TOWER_TYPE_AA
					end

					-- 2 tower 2 me
					if remainingHealth > 0 and remainingHealth > (myDamage + towerDamage) * 2 then
						return self.TowerTarget, TOWER_TYPE_IGNORE
					end



					-- time = self.TowerHitTime - Helper:GetTime() --Orbwalker:GetNextAttackTime() - Helper:GetTime() + Orbwalker:GetWindUp() + GetDistance(self.TowerTarget.visionPos, myHero.visionPos) / MyHero.ProjectileSpeed - 0.07
					-- remainingHealth = VP:GetPredictedHealth(self.TowerTarget, time)

					-- if remainingHealth < 0 then
					-- 	return self.TowerTarget, TOWER_TYPE_SKILL
					-- end

					-- -- need skill
					-- if remainingHealth < 0 then
					-- 	return self.TowerTarget, TOWER_TYPE_SKILL
					-- end

					-- remainingHealth = VP:GetPredictedHealth2(self.TowerTarget, time)

					-- -- need skill
					-- remainingHealth = remainingHealth - myDamage
					-- if remainingHealth < 0 then
					-- 	return self.TowerTarget, TOWER_TYPE_SKILL
					-- end

				end
			end

			function _Minions:WaitForCannon()
				local cans = {}
				for _, min in pairs(self.EnemyMinions.objects) do
					if Helper:GetDistance(min) <= 2000 and Data:IsCannonMinion(min) then
						table.insert(cans, min)
					end
				end

				for i, Cannon in pairs(cans) do
					if Orbwalker:CanOrbwalkTargetCustomRange(Cannon, self.AttackRangeBuffer) then
						if DamagePred:GetPred(Cannon, PRED_TWO_HITS) < self:MyDamage(Cannon) then
							return Cannon
						end
					end
				end
			end

			function _Minions:FindUnkillable()
				local cannon = self:WaitForCannon()

				if cannon then
					if DamagePred:GetPred(cannon, PRED_UNKILLABLE) < self:MyDamage(cannon) then
						if minion ~= self.LastHitMinion then
							local minionhealth = DamagePred:GetPred(cannon, PRED_UNKILLABLE)
							if minionhealth < 0 then
								return cannon
							end
						end
					end
				end

				for i, minion in ipairs(self.EnemyMinions.objects) do
					if minion ~= self.LastHitMinion then
						local minionhealth = DamagePred:GetPred(minion, PRED_UNKILLABLE)
						if minionhealth < 0 then
							return minion
						end
					end
				end
			end

			function _Minions:FindKillable()
				local cannon = self:WaitForCannon()

				if cannon then
					if Orbwalker:CanOrbwalkTarget(cannon, self.AttackRangeBuffer) and DamagePred:GetPred(cannon, PRED_LAST_HIT) < self:MyDamage(cannon) then
						local mydmg = self:MyDamage(cannon)
						local minionhealth = DamagePred:GetPred(cannon, PRED_LAST_HIT)
						if minionhealth < mydmg and minionhealth > self.LowerLimit then
							return cannon
						end
					end
					return
				end

				for i, minion in ipairs(self.EnemyMinions.objects) do
					local minionhealth = DamagePred:GetPred(minion, PRED_LAST_HIT)
					local mydmg = self:MyDamage(minion)

					if Orbwalker:CanOrbwalkTarget(minion) and minionhealth < mydmg and minionhealth > self.LowerLimit then
						self.LastHitMinion = minion
						return minion
					end
				end
			end

			function _Minions:ShouldWait()
				for i, minion in ipairs(self.EnemyMinions.objects) do
					local mydmg = self:MyDamage(minion)
					local minionhealth = DamagePred:GetPred(minion, PRED_TWO_HITS)
					if Orbwalker:CanOrbwalkTarget(minion, self.AttackRangeBuffer) and minionhealth < mydmg then
						self.LastWait = Helper:GetTime()
						self.LastWaitedFor = minion
						return minion
					end
				end
			end

			function _Minions:TowerFarm()
				if ConfigurationMenu.SupportMode then
					MyHero:Move()
					return
				end

				self.EnemyMinions:update()
				--DamagePred:Reset()

				local target = self:FindKillable()

				if target then
					self.KillableMinion = target
				else
					self.KillableMinion = nil
				end

				if target then
					Orbwalker:Orbwalk(target)
					return
				end

				if LaneClearSkillsMenu.MinMana and myHero.mana / myHero.maxMana * 100 >= LaneClearSkillsMenu.MinMana then
					target = self:FindUnkillable()
					if target then
						target, skill = self:GetKillableSkillMinion(false, target)
						if target then
							self:CastOnMinion(target, skill)
						end
					end
				end

				target, _type = self:GetTowerMinion()
				if target and _type == TOWER_TYPE_AA then
					Orbwalker:Orbwalk(target)
					return
				end

				if target and _type == TOWER_TYPE_SKILL then
					self:LastHitWithSkill(target)
					return
				end

				if target and _type == TOWER_TYPE_IGNORE and Keys.LaneClear then
					self:LaneClear(true)
					return
				end

				MyHero:Move()
			end

			function _Minions:LaneClear(ignoreTower)
				if ConfigurationMenu.SupportMode then
					MyHero:Move()
					return
				end

				if ValidTarget(self.TowerTarget) and not ignoreTower then
					self:TowerFarm()
					return
				end

				self.EnemyMinions:update()
				--DamagePred:Reset()

				if LaneClearSkillsMenu.MinMana and myHero.mana / myHero.maxMana * 100 >= LaneClearSkillsMenu.MinMana then
					local target, skill = self:GetKillableSkillMinion(true)
					if target then
						self:CastOnMinion(target, skill)
					end
				end

				local target = self:FindKillable()

				if not target and Structures:CanOrbwalkStructure() then
					Orbwalker:OrbwalkIgnoreChecks(Structures:GetTargetStructure())
					return
				end

				if Orbwalker:CanOrbwalkTarget(Crosshair.Attack_Crosshair.target) and (not LaneClearMenu.MinionPriority or not target) then
					Orbwalker:Orbwalk(Crosshair.Attack_Crosshair.target)
					return
				end


				if target then
					self.KillableMinion = target
				else
					self.KillableMinion = nil
				end

				if target and Orbwalker:CanShoot() then
					Orbwalker:Orbwalk(target)
					return
				end

				local waitMinion = self:ShouldWait()
				 if (not waitMinion and Helper:GetTime() > self.LastWait + 500) or not Orbwalker:CanOrbwalkTarget(self.LastWaitedFor) then
				 	Orbwalker:Orbwalk(self:GetLaneClearTarget())
				elseif Helper:GetTime() > self.LastMove then
					self.LastMove = Helper:GetTime() + 100
					MyHero:Move()
				 end

				if waitMinion and not self.KillableMinion then
					self.AlmostKillable = waitMinion
				elseif Helper:GetTime() > self.LastWait + 500 or not ValidTarget(waitMinion) then
					self.AlmostKillable = nil
				end
			end

			function _Minions:LastHit()
				if ConfigurationMenu.SupportMode then
					MyHero:Move()
					return
				end

				if ValidTarget(self.TowerTarget) then
					self:TowerFarm()
					return
				end

				self.EnemyMinions:update()
				--DamagePred:Reset()

				target = self:FindKillable()


				if target then
					self.KillableMinion = target
				else
					self.KillableMinion = nil
				end

				if target then
					Orbwalker:Orbwalk(target)
					return
				end

				-- if myHero.mana / myHero.maxMana * 100 >= LastHitSkillsMenu.MinMana then
				-- 	target = self:FindKillable()
				-- 	if target then
				-- 		Orbwalker:Orbwalk(target)
				-- 		return
				-- 	end
				-- end

				MyHero:Move()
			end

			function _Minions:MarkerOnly()
				self.EnemyMinions:update()

				local target = self:FindKillable()

				if target then
					self.KillableMinion = target
				else
					self.KillableMinion = nil
				end

				if target then
					return
				end

				local waitMinion = self:ShouldWait()

				if waitMinion and not self.KillableMinion then
					self.AlmostKillable = waitMinion
				elseif Helper:GetTime() > self.LastWait + 500 or not ValidTarget(waitMinion) then
					self.AlmostKillable = nil
				end
			end

			function _Minions:CastOnMinion(Minion, Skill)
				local dmgString = "";
				if Skill.Key == _Q then
					dmgString = "Q"
				elseif Skill.Key == _W then
					dmgString = "W"
				elseif Skill.Key == _E then
					dmgString = "E"
				elseif Skill.Key == _R then
					dmgString = "R"
				end

				if ValidTarget(Minion, Skill.Range) then
					if Skill.Type == SPELL_TARGETED then
						CastSpell(Skill.Key, Minion)
					elseif Skill.Type == SPELL_SELF_AT_MOUSE then
						CastSpell(Skill.Key, Minion.x, Minion.z)
					elseif Skill.Type == SPELL_LINEAR then
						Skill:Cast(Minion)
					elseif Skill.Type == SPELL_LINEAR_COL then
						local pred = Skill:GetLinePrediction(Minion)
						if pred then
							CastSpell(Skill.Key, pred.x, pred.z)
						end
					else
						CastSpell(Skill.Key)
					end
				end
			end

			function _Minions:GetKillableSkillMinion(isLaneClear, fixedMinion)
				for i, Miniona in ipairs(self.EnemyMinions.objects) do
					Minion = fixedMinion or Miniona
					if Minion ~= self.LastHitMinion then
						for _, Skill in pairs(Skills:GetLastHitSkills()) do
							local dmgString = "";
							if Skill.Key == _Q then
								dmgString = "Q"
							elseif Skill.Key == _W then
								dmgString = "W"
							elseif Skill.Key == _E then
								dmgString = "E"
							elseif Skill.Key == _R then
								dmgString = "R"
							end

							if myHero:CanUseSpell(Skill.Key) == READY and ValidTarget(Minion, Skill.Range) and (isLaneClear and LaneClearSkillsMenu["FarmSkill"..Skill.RawName]) then
								local _Damage = getDmg(dmgString, Minion, myHero)
								if Skill.Type == SPELL_LINEAR or Skill.Type == SPELL_LINEAR_COL then
									local minionhealth = DamagePred:GetPred(Minion, PRED_SKILL, Skill)
									if _Damage > minionhealth and minionhealth > self.LowerLimit then
										local pred = Skill:GetLinePrediction(Minion)
										if pred then
											return Minion, Skill
										end
									end
								elseif _Damage > Minion.health then
									return Minion, Skill
								end
							end
						end
					end
					if fixedMinion then
						return
					end
				end
			end

			function _Minions:PushWithSkills()
				for i, minion in ipairs(self.EnemyMinions.objects) do
					if minion ~= self.LastHitMinion then
						self:LastHitWithSkill(minion, true)
					end
				end
			end

			function _Minions:BonusDamage(minion)
				local AD = myHero:CalcDamage(minion, myHero.totalDamage)
				local BONUS = 0
				if myHero.charName == 'Vayne' then
					if myHero:GetSpellData(_Q).level > 0 and myHero:CanUseSpell(_Q) == SUPRESSED then
						BONUS = BONUS + myHero:CalcDamage(minion, ((0.05 * myHero:GetSpellData(_Q).level) + 0.25 ) * myHero.totalDamage)
					end
					if not VayneCBAdded then
						VayneCBAdded = true
						function VayneParticle(obj)
							if GetDistance(obj) < 1000 and obj.name:lower():find("vayne_w_ring2.troy") then
								VayneWParticle = obj
							end
						end
						AddCreateObjCallback(VayneParticle)
					end
					if VayneWParticle and VayneWParticle.valid and GetDistance(VayneWParticle, minion) < 10 then
						BONUS = BONUS + 10 + 10 * myHero:GetSpellData(_W).level + (0.03 + (0.01 * myHero:GetSpellData(_W).level)) * minion.maxHealth
					end
				elseif myHero.charName == 'Teemo' and myHero:GetSpellData(_E).level > 0 then
					BONUS = BONUS + myHero:CalcMagicDamage(minion, (myHero:GetSpellData(_E).level * 10) + (myHero.ap * 0.3) )
				elseif myHero.charName == 'Corki' then
					BONUS = BONUS + myHero.totalDamage/10
				elseif myHero.charName == 'MissFortune' and myHero:GetSpellData(_W).level > 0 then
					BONUS = BONUS + myHero:CalcMagicDamage(minion, (4 + 2 * myHero:GetSpellData(_W).level) + (myHero.ap/20))
				elseif myHero.charName == 'Varus' and myHero:GetSpellData(_W).level > 0 then
					BONUS = BONUS + (6 + (myHero:GetSpellData(_W).level * 4) + (myHero.ap * 0.25))
				elseif myHero.charName == 'Caitlyn' then
					if not CallbackCaitlynAdded then
						function CaitlynParticle(obj)
							if GetDistance(obj) < 100 and obj.name:lower():find("caitlyn_headshot_rdy") then
								HeadShotParticle = obj
							end
						end
						AddCreateObjCallback(CaitlynParticle)
						CallbackCaitlynAdded = true
					end
					if HeadShotParticle and HeadShotParticle.valid then
						BONUS = BONUS + AD * 1.5
					end
				elseif myHero.charName == 'Orianna' then



					-- if not CallbackOriannaAdded then
					-- 	OriBuffStacks = 0
					-- 	function OriannaOnGainBuff(unit, buff)
					-- 		if unit.isMe and buff.name == "orianapowerdaggerdisplay" then
					-- 			OriBuffStacks = 1
					-- 		end
					-- 	end
					-- 	AdvancedCallback:bind("OnGainBuff", OriannaOnGainBuff)

					-- 	function OriannaOnLoseBuff(unit, buff)
					-- 		if unit.isMe and buff.name == "orianapowerdaggerdisplay" then
					-- 			OriBuffStacks = 0
					-- 		end
					-- 	end
					-- 	AdvancedCallback:bind("OnLoseBuff", OriannaOnLoseBuff)

					-- 	function OriannaOnUpdateBuff(unit, buff)
					-- 		if unit.isMe and buff.name == "orianapowerdaggerdisplay" then
					-- 			print("Passive stacks changed to "..buff.stack)
					-- 		end
					-- 	end
					-- 	AdvancedCallback:bind("OnUpdateBuff", OriannaOnUpdateBuff)
					-- 	CallbackOriannaAdded = true
					-- end




					BONUS = BONUS + myHero:CalcMagicDamage(minion, 10 + 8 * ((myHero.level - 1) % 3))
				elseif myHero.charName == 'TwistedFate' then
					if not TFCallbackAdded then
						function TFParticle(obj)
							if GetDistance(obj) < 100 and obj.name:lower():find("cardmaster_stackready.troy") then
								TFEParticle = obj
							elseif GetDistance(obj) < 100 and obj.name:lower():find("card_blue.troy") then
								TFWParticle = obj
							end
						end
						AddCreateObjCallback(TFParticle)
						TFCallbackAdded = true
					end
					if TFEParticle and TFEParticle.valid then
						BONUS = BONUS + myHero:CalcMagicDamage(minion, myHero:GetSpellData(_E).level * 15 + 40 + 0.5 * myHero.ap)
					end
					if TFWParticle and TFWParticle.valid then
						BONUS = BONUS + math.max(myHero:CalcMagicDamage(minion, myHero:GetSpellData(_W).level * 20 + 20 + 0.5 * myHero.ap) - 40, 0)
					end
				elseif myHero.charName == 'Nasus' and VIP_USER then
					if myHero:GetSpellData(_Q).level > 0 and myHero:CanUseSpell(_Q) == SUPRESSED then
						local Qdamage = {30, 50, 70, 90, 110}
						NasusQStacks = NasusQStacks or 0
						BONUS = BONUS + myHero:CalcDamage(minion, 10 + 20 * (myHero:GetSpellData(_Q).level) + NasusQStacks)
						if not RecvPacketNasusAdded then
							function NasusOnRecvPacket(p)
								if p.header == 0xFE and p.size == 0xC then
									p.pos = 1
									pNetworkID = p:DecodeF()
									unk01 = p:Decode2()
									unk02 = p:Decode1()
									stack = p:Decode4()
									if pNetworkID == myHero.networkID then
										NasusQStacks = stack
									end
								end
							end
							RecvPacketNasusAdded = true
							AddRecvPacketCallback(NasusOnRecvPacket)
						end
					end
				end

				return BONUS
			end

			function _Minions:GetLowestHealthMinion()
				for i =1, #self.EnemyMinions.objects, 1 do
					local Minion = self.EnemyMinions.objects[i]
					if Orbwalker:CanOrbwalkTarget(Minion) then
						return Minion
					end
				end
			end

			function _Minions:GetSecondLowestHealthMinion()
				local found = nil
				for i =1, #self.EnemyMinions.objects, 1 do
					local Minion = self.EnemyMinions.objects[i]
					if Orbwalker:CanOrbwalkTarget(Minion) and found then
						return Minion
					elseif Orbwalker:CanOrbwalkTarget(Minion) then
						found = Minion
					end
				end
				return found
			end

			--[[
			_ChampionBuffs Class
			]]

			class '_ChampionBuffs' ChampionBuffs = nil

			function _ChampionBuffs:__init()
				AddCreateObjCallback(function(Obj) self:_OnCreateObj(Obj) end)
				AddDeleteObjCallback(function(Obj) self:_OnDeleteObj(Obj) end)

				self.HasPassive = false
				self.RangerStacks = 0
				self.qBuff = 0

				ChampionBuffs = self
			end

			function _ChampionBuffs:_OnCreateObj(object)
				if myHero.dead then return end
				if object.name:lower():find("caitlyn_headshot_rdy") and Helper:GetDistance(object) < 65 then self.caitlynPassive = true end
				if object.name:lower():find("Lucian_P_buf.troy") and Helper:GetDistance(object) < 65 then self.LucianPassive = true end
				if object.name == "RengarPassiveMax.troy" and Helper:GetDistance(object) < 65 then self.rengarStacks = 5 end
				if object.name == "RighteousFuryHalo_buf.troy" and Helper:GetDistance(object) < 65 then self.kayleBuff = true end
				if object.name == "Draven_Q_buf.troy" and Helper:GetDistance(object) < 65 then self.qBuff = (self.qBuff >= 0 and self.qBuff + 1) or 0 end
				if object.name == "Jayce_Hex_Buff_Ready.troy" and Helper:GetDistance(object) < 65 then self.jayceWcasted = true end
			end

			function _ChampionBuffs:_OnDeleteObj(object)
				if object.name:lower():find("caitlyn_headshot_rdy") and Helper:GetDistance(object) < 65 then self.caitlynPassive = false end
				if object.name:lower():find("Lucian_P_buf.troy") and Helper:GetDistance(object) < 65 then self.LucianPassive = true end
				if object.name == 'Lucian_R_self.troy' and LucianCulling == true and Helper:GetDistance(object) < 65 then self.LucianCulling = false end
				if object.name == "RengarPassiveMax.troy" and Helper:GetDistance(object) < 65 then self.rengarStacks = 0 end
				if object.name == "RighteousFuryHalo_buf.troy" and Helper:GetDistance(object) < 65 then self.kayleBuff = false end
				if object.name == "Draven_Q_buf.troy" and Helper:GetDistance(object) < 600 then self.qBuff = self.qBuff - 1 end
				if object.name == "Jayce_Hex_Buff_Ready.troy" and Helper:GetDistance(object) < 65 then self.jayceWcasted = false end
			end

			function _ChampionBuffs:GetBonusDamage()
				local additionalDamage = {
					Teemo       = myHero:GetSpellData(_E).level > 0 and ((GetSpellData(_E).level * 10) + (myHero.ap * 0.3)) or 0,
					Vayne       = myHero:GetSpellData(_Q).level > 0 and myHero:CanUseSpell(_Q) == SUPRESSED and (((0.05*myHero:GetSpellData(_Q).level) + 0.25 )*(myHero.totalDamage)) or 0,
					Corki       = myHero.totalDamage/10,
					Caitlyn     = self.caitlynPassive and myHero.totalDamage * 1.5 or 0,
					Rengar      = myHero:GetSpellData(_Q).level > 0 and myHero:CanUseSpell(_Q) == SUPRESSED and (self.rengarStacks == 5 and (30*myHero:GetSpellData(_Q).level)+myHero.totalDamage or (30*myHero:GetSpellData(_Q).level)) or 0 ,
					MissFortune = myHero:GetSpellData(_W).level > 0 and ((2+2*myHero:GetSpellData(_W).level) + (myHero.ap*0.05)) or 0,
					Sivir       = myHero:GetSpellData(_W).level > 0 and myHero:CanUseSpell(_W) == SUPRESSED and (5+(15*myHero:GetSpellData(_W).level)) or 0,
					Orianna     = (8*(((myHero.level - 1) / 3) + 1) + 2 + 0.15*myHero.ap),
					Draven      = myHero:GetSpellData(_Q).level > 0 and self.qBuff > 0 and (myHero.totalDamage*(0.35 + (0.1 * myHero:GetSpellData(_Q).level))) or 0,
					Kayle       = self.kayleBuff and (10 + (10*myHero:GetSpellData(_E).level) + (0.4*myHero.ap)) or 0,
					Jayce       = self.jayceWcasted and ((myHero.totalDamage*(15*myHero:GetSpellData(_W).level+55)/100) - myHero.totalDamage) or 0,
					Lucian      = self.LucianPassive and myHero.totalDamage or 0
				}
				return additionalDamage[myHero.charName] or 0
			end

			--[[
			_Jungle Class
			]]

			class '_Jungle' Jungle = nil

			function _Jungle:__init()
				self.JungleMonsters = {}
				Jungle = self
				for i = 0, objManager.maxObjects do
					local object = objManager:getObject(i)
					if Data:IsJungleMinion(object) then
						table.insert(self.JungleMonsters, object)
					end
				end

				AddCreateObjCallback(function(Object) self:_OnCreateObj(Object) end)
				AddDeleteObjCallback(function(Object) self:_OnDeleteObj(Object) end)
			end

			function _Jungle:_OnCreateObj(Object)
				if Data:IsJungleMinion(Object) then
					table.insert(self.JungleMonsters, Object)
				end
			end

			function _Jungle:_OnDeleteObj(Object)
				if Data:IsJungleMinion(Object) then
					for i, Obj in pairs(self.JungleMonsters) do
						if obj == Object then
							table.remove(self.JungleMonsters, i)
						end
					end
				end
			end

			function _Jungle:GetJungleMonsters()
				return self.JungleMonsters
			end

			function _Jungle:GetAttackableMonster()
				local HighestPriorityMonster =  nil
				local Priority = 0
				for _, Monster in pairs(self.JungleMonsters) do
					if Orbwalker:CanOrbwalkTarget(Monster) then
						local CurrentPriority = Data:GetJunglePriority(Monster.name)
						if Monster.health < MyHero:GetTotalAttackDamageAgainstTarget(Monster) then
							return Monster
						elseif not HighestPriorityMonster then
							HighestPriorityMonster = Monster
							Priority = CurrentPriority
						else
							if CurrentPriority < Priority then
								HighestPriorityMonster = Monster
								Priority = CurrentPriority
							end
						end
					end
				end
				return HighestPriorityMonster
			end

			function _Jungle:GetFocusedMonster()
				if GetTarget() and Data:IsJungleMinion(GetTarget()) then
					return GetTarget()
				end
			end

			class '_Structures' Structures = nil

			function _Structures:__init()
				Structures = self
				self.TowerCollisionRange = 88.4
				self.InhibCollisionRange = 205
				self.NexusCollisionRange = 300
				self.TowerRange = 950
				self.EnemyTowers = {}
				self.AllyTowers = {}

				for i = 1, objManager.maxObjects do
					local Object = objManager:getObject(i)
					if Object and Object.type == "obj_AI_Turret" then
						if Object.team == myHero.team then
							table.insert(self.AllyTowers, Object)
						else
							table.insert(self.EnemyTowers, Object)
						end
					end
				end

				AddDeleteObjCallback(function(obj) self:_OnDeleteObj(obj) end)
			end

			function _Structures:_OnDeleteObj(Object)
				for i, Tower in pairs(self.AllyTowers) do
					if Object == Tower then
						table.remove(self.AllyTowers, i)
						return
					end
				end
				for i, Tower in pairs(self.EnemyTowers) do
					if Object == Tower then
						table.remove(self.EnemyTowers, i)
						return
					end
				end
			end

			function _Structures:TowerTargetted()
				return GetTarget() and GetTarget().type == "obj_AI_Turret" and GetTarget().team ~= myHero.team
			end

			function _Structures:InhibTargetted()
				return GetTarget() and GetTarget().type == "obj_BarracksDampener" and GetTarget().team ~= myHero.team
			end

			function _Structures:NexusTargetted()
				return GetTarget() and GetTarget().type == "obj_HQ" and GetTarget().team ~= myHero.team
			end

			function _Structures:CanOrbwalkStructure()
				return self:CanOrbwalkTower() or self:CanOrbwalkInhib() or self:CanOrbwalkNexus()
			end

			function _Structures:GetTargetStructure()
				return GetTarget()
			end

			function _Structures:CanOrbwalkTower()
				return self:TowerTargetted() and Helper:GetDistance(GetTarget()) - self.TowerCollisionRange < MyHero.TrueRange
			end

			function _Structures:CanOrbwalkInhib()
				return self:InhibTargetted() and Helper:GetDistance(GetTarget()) - self.InhibCollisionRange < MyHero.TrueRange
			end

			function _Structures:CanOrbwalkNexus()
				return self:NexusTargetted() and Helper:GetDistance(GetTarget()) - self.NexusCollisionRange < MyHero.TrueRange
			end

			function _Structures:PositionInEnemyTowerRange(Pos)
				for _, Tower in pairs(self.EnemyTowers) do
					if Helper:GetDistance(Tower, Pos) <= self.TowerRange then
						return true
					end
				end
				return false
			end

			function _Structures:PositionInAllyTowerRange(Pos)
				for _, Tower in pairs(self.AllyTowers) do
					if Helper:GetDistance(Tower, Pos) <= self.TowerRange then
						return true
					end
				end
				return false
			end

			function _Structures:GetClosestEnemyTower(Pos)
				local ClosestTower, Distance = nil, 0
				for i, Tower in pairs(self.EnemyTowers) do
					if not Tower or not Pos then return end
					if not ClosestTower then
						ClosestTower, Distance = Tower, Helper:GetDistance(Pos, Tower)
					elseif Helper:GetDistance(Pos, Tower) < Distance then
						ClosestTower, Distance = Tower, Helper:GetDistance(Pos, Tower)
					end
				end
				return ClosestTower
			end

			function _Structures:GetClosestAllyTower(Pos)
				local ClosestTower, Distance = nil, 0
				for _, Tower in pairs(self.AllyTowers) do
					if not ClosestTower then
						ClosestTower, Distance = Tower, Helper:GetDistance(Pos, Tower)
					elseif Helper:GetDistance(Pos, Tower) < Distance then
						ClosestTower, Distance = Tower, Helper:GetDistance(Pos, Tower)
					end
				end
				return ClosestTower
			end

			--[[
			_Skills Class
			]]

			class '_Skills' Skills = nil

			function _Skills:__init()
				self.SkillsList = {}
				Skills = self
				AddTickCallback(function() self:_OnTick() end)
			end

			function _Skills:_OnTick()
				for _, Skill in pairs(self.SkillsList) do
					if (Keys.AutoCarry and SkillsMenu[Skill.RawName].AutoCarry) or
						(Keys.MixedMode and SkillsMenu[Skill.RawName].MixedMode) or
						(Keys.LaneClear and SkillsMenu[Skill.RawName].LaneClear) then
						Skill.Active = true
					else
						Skill.Active = false
					end
					Skill.Killsteal = SkillsMenu[Skill.RawName].Killsteal
				end
				self:Killsteal()
			end

			function _Skills:CastAll(Target)
				for _, Skill in ipairs(self.SkillsList) do
					if Skill.Enabled then
						Skill:Cast(Target)
					end
				end
			end

			function _Skills:GetSkill(Key)
				for _, Skill in pairs(self.SkillsList) do
					if Skill.Key == Key then
						return Skill
					end
				end
			end

			function _Skills:HasSkillReady()
				for _, Skill in pairs(self.SkillsList) do
					if Skill.Ready then
						return true
					end
				end
			end

			function _Skills:NewSkill(enabled, key, range, displayName, type, minMana, afterAttack, reqAttackTarget, speed, delay, width, collision, isReset)
				return _Skill(enabled, key, range, displayName, type, minMana, afterAttack, reqAttackTarget, speed, delay, width, collision, isReset, true)
			end

			function _Skills:DisableAll()
				for _, Skill in pairs(self.SkillsList) do
					Skill.Enabled = false
				end
			end

			function _Skills:GetLastHitSkills()
				local Skills = {}
				for _, Skill in pairs(self.SkillsList) do
					if Skill.Type == SPELL_TARGETED or Skill.IsReset or Skill.Type == SPELL_LINEAR_COL or Skill.Type == SPELL_LINEAR then
						table.insert(Skills, Skill)
					end
				end
				return Skills
			end

			function _Skills:Killsteal()
				for _, Enemy in pairs(GetEnemyHeroes()) do
					for _, Skill in pairs(self.SkillsList) do
						local dmgString = "";
						if Skill.Key == _Q then
							dmgString = "Q"
						elseif Skill.Key == _W then
							dmgString = "W"
						elseif Skill.Key == _E then
							dmgString = "E"
						elseif Skill.Key == _R then
							dmgString = "R"
						end
						if Skill.Killsteal and ValidTarget(Enemy, Skill.Range) then
							if Enemy.health < getDmg(dmgString, Enemy, myHero) then
								Skill:Cast(Enemy, true)
							end
						end
					end
				end
			end

			--[[
			_Skill Class
			]]

			class '_Skill'

			SPELL_TARGETED = 1
			SPELL_LINEAR = 2
			SPELL_CIRCLE = 3
			SPELL_CONE = 4
			SPELL_LINEAR_COL = 5
			SPELL_SELF = 6
			SPELL_SELF_AT_MOUSE = 7
			AutoCarry.SPELL_TARGETED = 1
			AutoCarry.SPELL_LINEAR = 2
			AutoCarry.SPELL_CIRCLE = 3
			AutoCarry.SPELL_CONE = 4
			AutoCarry.SPELL_LINEAR_COL = 5
			AutoCarry.SPELL_SELF = 6
			AutoCarry.SPELL_SELF_AT_MOUSE = 7

			-- --[[
			-- 		Initialise _Skill class

			-- 		enabled  			Boolean - set true for auto carry to automatically cast it, false for manual control in plugin
			-- 		key 				Spell key, e.g _Q
			-- 		range 				Spell range
			-- 		displayName 		The name to display in menus
			-- 		type 				SPELL_TARGETED, SPELL_LINEAR, SPELL_CIRCLE, SPELL_CONE, SPELL_LINEAR_COL, SPELL_SELF, SPELL_SELF_AT_MOUSE
			-- 		minMana 			Minimum percentage mana before cast is allowed
			-- 		afterAttack 		Boolean - set true to only cast right after an auto attack
			-- 		reqAttackTarget 	Boolean - set true to only cast if a target is in attack range
			-- 		speed 				Speed of the projectile for skillshots
			-- 		delay 				Delay of the spell for skillshots
			-- 		width 				Width of the projectile for skillshots
			-- 		collision 			Boolean - set true to check minion collision before casting

			-- ]]
			function _Skill:__init(enabled, key, range, displayName, type, minMana, afterAttack, reqAttackTarget, speed, delay, width, collision, isReset, custom)
				self.Key = key
				self.Range = range
				self.DisplayName = displayName
				self.RawName = self.DisplayName:gsub("[^A-Za-z0-9]", "")
				self.Type = type
				self.MinMana = minMana or 0
				self.AfterAttack = afterAttack or false
				self.ReqAttackTarget = reqAttackTarget or false
				self.Speed = speed or 0
				self.Delay = delay or 0
				self.Width = width or 0
				self.Collision = collision
				self.IsReset = isReset or false
				self.IsCustom = custom
				self.Active = true
				self.Enabled = enabled or false
				self.Ready = false
				self.Killsteal = false

				AddTickCallback(function() self:_OnTick() end)

				table.insert(Skills.SkillsList, self)
			end

			function _Skill:_OnTick()
				self.Ready = myHero:CanUseSpell(self.Key) == READY
			end

			function _Skill:Cast(Target, ForceCast)
				if not ForceCast then
					if (not self.Active and self.Enabled) or (not self.Enabled and not self.IsCustom) then
						return
					elseif self.AfterAttack and not Orbwalker:IsAfterAttack() then
						return
					elseif (self.ReqAttackTarget and not Orbwalker:CanOrbwalkTarget(Target)) then
						return
					end
				end


				if not self:IsReady() then
					return
				end

				if self.Type == SPELL_SELF then
					CastSpell(self.Key)
				elseif self.Type == SPELL_SELF_AT_MOUSE then
					CastSpell(self.Key, mousePos.x, mousePos.z)
				elseif self.Type == SPELL_TARGETED then
					if ValidTarget(Target, self.Range) then
						CastSpell(self.Key, Target)
					end
				elseif self.Type == SPELL_LINEAR or self.Type == SPELL_LINEAR_COL or self.Type == SPELL_CONE then
					if ValidTarget(Target) then
						local predPos = self:GetPrediction(Target, true, ForceCast)
						if predPos and GetDistance(predPos) <= self.Range then
							CastSpell(self.Key, predPos.x, predPos.z)
						end
					end
				elseif self.Type == SPELL_CIRCLE then
					if ValidTarget(Target) then
						local predPos = self:GetPrediction(Target, false, ForceCast)
						if predPos and GetDistance(predPos) <= self.Range then
							CastSpell(self.Key, predPos.x, predPos.z)
						end
					end
				end
			end

			function _Skill:ForceCast(Target)
				self:Cast(Target, true)
			end

			function _Skill:GetPrediction(Target, isLine, forceCast)
				local isCol = false
				if self.Collision or self.Type == SPELL_LINEAR_COL then
					isCol = true
				end

				if forceCast then
					isCol = false
				end

				if VIP_USER then
					if isLine then
						CastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, self.Delay / 1000, self.Width, self.Range, self.Speed * 1000, myHero, isCol)
					else
						CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(Target, self.Delay / 1000, self.Width, self.Range, self.Speed * 1000, myHero, isCol)
					end

					if HitChance >= 2 then
						return CastPosition
					end
				elseif not VIP_USER then
					pred = TargetPrediction(self.Range, self.Speed, self.Delay, self.Width)
					pred = pred:GetPrediction(Target)

					if isCol then
						local collision = self:GetCollision(pred)
						if not collision then
							return pred
						end
					else
						return pred
					end
				end
			end

			function _Skill:GetLinePrediction(Target)
				return self:GetPrediction(Target, true)
			end

			function _Skill:GetCirclePrediction(Target)
				return self:GetPrediction(Target, false)
			end

			function _Skill:GetCollision(pos)
				if VIP_USER and self.Collision then
					local col = Collision(self.Range, self.Speed*1000, self.Delay/1000, self.Width)
					return col:GetMinionCollision(myHero, pos)
				elseif self.Collision then
					for _, Minion in pairs(Minions.EnemyMinions.objects) do
						if ValidTarget(Minion) and myHero.x ~= Minion.x then
							local myX = myHero.x
							local myZ = myHero.z
							local tarX = pos.x
							local tarZ = pos.z
							local deltaX = myX - tarX
							local deltaZ = myZ - tarZ
							local m = deltaZ/deltaX
							local c = myX - m*myX
							local minionX = Minion.x
							local minionZ = Minion.z
							local distanc = (math.abs(minionZ - m*minionX - c))/(math.sqrt(m*m+1))
							if distanc < self.Width and ((tarX - myX)*(tarX - myX) + (tarZ - myZ)*(tarZ - myZ)) > ((tarX - minionX)*(tarX - minionX) + (tarZ - minionZ)*(tarZ - minionZ)) then
								return true
							end
						end
					end
					return false
				end
			end

			function _Skill:GetHitChance(pred)
				if VIP_USER then
					return pred:GetHitChance(target) > ConfigMenu.HitChance/100
				end
			end

			function _Skill:GetRange()
				return self.reqAttackTarget and MyHero.TrueRange or self.Range
			end

			function _Skill:IsReady()
				return myHero:CanUseSpell(self.Key) == READY
			end

			--[[
			_Items Class
			]]

			class '_Items' Items = nil

			function _Items:__init()
				self.ItemList = {}
				Items = self

				AddTickCallback(function() self:_OnTick() end)
			end

			function _Items:_OnTick()
				for _, Item in pairs(self.ItemList) do
					if Keys.AutoCarry and AutoCarryMenu[Item.RawName.."AutoCarry"] or
						Keys.MixedMode and MixedModeMenu[Item.RawName.."MixedMode"] or
						Keys.LaneClear and LaneClearMenu[Item.RawName.."LaneClear"] then
						Item.Active = true
					else
						Item.Active = false
					end
				end
			end

			function _Items:UseAll(Target)
				if Target and Target.type == myHero.type then
					for _, Item in pairs(self.ItemList) do
						Item:Use(Target)
					end
				end
			end

			function _Items:UseItem(ID, Target)
				for _, Item in pairs(self.ItemList) do
					if Item.ID == ID then
						Item:Use(Target)
					end
				end
			end

			function _Items:GetItem(ID)
				for _, Item in pairs(self.ItemList) do
					if Item.ID == ID then
						return Item
					end
				end
			end

			function _Items:GetBotrkBonusLastHitDamage(StartingDamage, Target)
				local _BonusDamage = 0
				if GetInventoryHaveItem(3153) then
					if ValidTarget(Target) then
						_BonusDamage = Target.health / 20
						if _BonusDamage >= 60 then
							_BonusDamage = 60
						end
					end
				end
				return _BonusDamage
			end

			--[[
			_Item Class
			]]

			class '_Item'

			--TODO: Add Muramana
			function _Item:__init(_Name, _ID, _RequiresTarget, _Range, _Override)
				self.Name = _Name
				self.RawName = self.Name:gsub("[^A-Za-z0-9]", "")
				self.ID = _ID
				self.RequiresTarget = _RequiresTarget
				self.Range = _Range
				self.Slot = nil
				self.Override = _Override
				self.Active = true
				self.Enabled = true

				table.insert(Items.ItemList, self)
			end

			function _Item:Use(Target)
				if self.Override then
					return self.Override()
				end
				if self.RequiresTarget and not Target then
					return
				end
				if not self.Active or not self.Enabled then
					return
				end

				self.Slot = GetInventorySlotItem(self.ID)

				if self.Slot then
					if self.ID == 3153 then -- BRK
						local _Menu = MenuManager:GetActiveMenu()
						if _Menu and _Menu.botrkSave then
							if  myHero.health <= myHero.maxHealth * 0.65 then
								CastSpell(self.Slot, Target)
							end
						elseif _Menu and _Menu.Active then
							CastSpell(self.Slot, Target)
						end
					elseif self.ID == 3042 then -- Muramana
						if not MuramanaIsActive() then
							MuramanaOn()
					end
					elseif self.ID == 3069 then -- Talisman of Ascension
						if Helper:CountAlliesInRange(600) > 0 then
							CastSpell(self.Slot)
					end
					elseif not self.RequiresTarget and Orbwalker:CanOrbwalkTarget(Target) then
						CastSpell(self.Slot)
					elseif self.RequiresTarget and ValidTarget(Target) and Helper:GetDistance(Target) <= self.Range then
						CastSpell(self.Slot, Target)
					end
				end
			end

			--[[
			_Helper Class
			]]

			class '_Helper' Helper = nil

			function _Helper:__init()
				self.Tick = 0
				self.Latency = 0
				self.Colour = {Green = 0x00FF00}
				self.EnemyTable = {}
				Helper = self
				self.EnemyTable = GetEnemyHeroes()
				self.AllyTable = GetAllyHeroes()
				self.AllHeroes = {}
				self:GetAllHeroes()
				self.DebugStrings = {}
				AddTickCallback(function() self:_OnTick() end)
				AddDrawCallback(function() self:_OnDraw() end)
			end

			function _Helper:_OnTick()
				self.Tick = Helper:GetTime()
				self.Latency = GetLatency()
			end

			function _Helper:GetTime()
				return os.clock() * 1000
			end

			function _Helper:GetDistance(p1, p2)
				p2 = p2 or myHero

				if p1.type == myHero.type then
					p1 = p1.visionPos
				end
				if p1.type == myHero.type then
					p2 = p2.visionPos
				end

				return math.sqrt(GetDistanceSqr(p1, p2))
			end

			function _Helper:StringContains(string, contains)
				return string:lower():find(contains)
			end

			function _Helper:DrawCircleObject(Object, Range, Colour, Thickness)
				if not Object then return end
				Thickness = Thickness and Thickness or 0
				for i = 0, Thickness do
					if DrawingMenu.LowFPSCir then
						self:DrawCircle2(Object.x, Object.y, Object.z, Range + i, Colour)
					else
						DrawCircle(Object.x, Object.y, Object.z, Range + i, Colour)
					end
				end
			end

			-- Low fps circles by barasia, vadash and viseversa
			function _Helper:DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
				radius = radius or 300
				quality = math.max(8,self:round(180/math.deg((math.asin((chordlength/(2*radius)))))))
				quality = 2 * math.pi / quality
				radius = radius*.92
				local points = {}
				for theta = 0, 2 * math.pi + quality, quality do
					local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
					points[#points + 1] = D3DXVECTOR2(c.x, c.y)
				end
				if DrawLines2 then
					DrawLines2(points, width or 1, color or 4294967295)
				end
			end

			function _Helper:round(num)
				if num >= 0 then return math.floor(num+.5) else return math.ceil(num-.5) end
			end

			function _Helper:DrawCircle2(x, y, z, radius, color)
				local vPos1 = Vector(x, y, z)
				local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
				local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
				local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
				if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
					self:DrawCircleNextLvl(x, y, z, radius, 1, color, 75)
				end
			end

			function _Helper:GetHitBoxDistance(Target)
				return Helper:GetDistance(Target) - Helper:GetDistance(Target, Target.minBBox)
			end

			function _Helper:TrimString(s)
				return s:find'^%s*$' and '' or s:match'^%s*(.*%S)'
			end

			function _Helper:GetClasses()
				return AutoCarry.Skills, AutoCarry.Keys, AutoCarry.Items, AutoCarry.Data, AutoCarry.Jungle, AutoCarry.Helper, AutoCarry.MyHero, AutoCarry.Minions, AutoCarry.Crosshair, AutoCarry.Orbwalker
			end

			function _Helper:ArgbFromMenu(menu)
				return ARGB(menu[1], menu[2], menu[3], menu[4])
			end

			function _Helper:DecToHex(Dec)
				local B, K, Hex, I, D = 16, "0123456789ABCDEF", "", 0
				while Dec > 0 do
					I = I + 1
					Dec, D = math.floor(Dec / B), math.fmod(Dec, B) + 1
					Hex = string.sub(K, D, D)..Hex
				end
				return Hex
			end

			function _Helper:HexFromMenu(menu)
				local argb = {}
				argb["a"] = menu[1]
				argb["r"] = menu[2]
				argb["g"] = menu[3]
				argb["b"] = menu[4]
				return tonumber(self:DecToHex(argb["a"]) .. self:DecToHex(argb["r"]) .. self:DecToHex(argb["g"]) .. self:DecToHex(argb["b"]), 16);
			end

			function _Helper:IsEvading()
				return _G.evade or _G.Evade
			end

			function _Helper:GetAllHeroes()
				for i = 1, heroManager.iCount do
					local hero = heroManager:GetHero(i)
					table.insert(self.AllHeroes, hero)
				end
			end

			function _Helper:Debug(str)
				table.insert(self.DebugStrings, str)
			end

			function _Helper:_OnDraw()
				local Height = 200
				for _, Str in pairs(self.DebugStrings) do
					DrawText(tostring(Str), 15, 100, Height, 0xFFFFFF00)
					Height = Height + 20
				end
				self.DebugStrings = {}
			end

			function _Helper:CountAlliesInRange(Range)
				local _Count = 0
				for _, Ally in pairs(GetAllyHeroes()) do
					if Ally ~= myHero and Helper:GetDistance(Ally) <= Range then
						_Count = _Count + 1
					end
				end
				return _Count
			end

			--[[
			_Wards Class
			]]

			class '_Wards' Wards = nil

			function _Wards:__init()
				self.EnemyWards = {}
				self.IncomingWards = {}
				self.AllyIncomingWards = {}
				self.PlacedWards = {}

				AddTickCallback(function() self:_OnTick() end)
				AddCreateObjCallback(function(Obj) self:_OnCreateObj(Obj) end)
				AddDeleteObjCallback(function(Obj) self:_OnDeleteObj(Obj) end)
				AddProcessSpellCallback(function(Unit, Spell) self:_OnProcessSpell(Unit, Spell) end)
				AddRecvPacketCallback(function(Packet) self:_OnReceivePacket(Packet) end)
				AdvancedCallback:bind("OnGainFocs")
				Plugins:RegisterPreAttack(function(target) self:PreAttack(target) end)
				Wards = self
			end

			function _Wards:_OnReceivePacket(p)
				if p.header == 49 then -- delete packet
					p.pos = 1
					local deaddid = p:DecodeF()
					local killerid = p:DecodeF()
					for networkID, ward in pairs(self.PlacedWards) do
						if ward and deaddid and networkID == deaddid and ward.vanga == 1 and (Helper:GetTime() - ward.spawnTime) > 200 then
							self.PlacedWards[networkID] = nil
						elseif ward and deaddid and networkID == deaddid and ward.vanga == 2 and killerid == 0 then
							self.PlacedWards[networkID] = nil
						end
					end
				end

				if p.header == 0xB4 then -- create packet
					p.pos = 12
					local wardtype2 = p:Decode1()
					p.pos = 1
					local creatorID = p:DecodeF()
					p.pos = p.pos + 20
					local creatorID2 = p:DecodeF()
					p.pos = 37
					local objectID = p:DecodeF()
					local objectX = p:DecodeF()
					local objectY = p:DecodeF()
					local objectZ = p:DecodeF()
					local objectX2 = p:DecodeF()
					local objectY2 = p:DecodeF()
					local objectZ2 = p:DecodeF()
					p:DecodeF()
					local warddet = p:Decode1()
					p.pos = p.pos + 4
					local warddet2 = p:Decode1()
					p.pos = 13
					local wardtype = p:Decode1()

					local objectID = DwordToFloat(AddNum(FloatToDword(objectID), 2))
					local creatorchamp = objManager:GetObjectByNetworkId(creatorID)
					local duration
					local range
					if creatorchamp and creatorchamp.team ~= myHero.team then return end

					if warddet == 0x3F and warddet2 == 0x33 and wardtype ~= 12 and wardtype ~= 48 then --wards 116 | wardtype 48 -> riven E
						if wardtype2 == 0x6E then
							self.PlacedWards[objectID] = {x = objectX2, y = objectY2, z = objectZ2, visionRange = 1100, spawnTime = Helper:GetTime(), duration = 60000, vanga = 1 }	-- WARDING TOTEM
					elseif wardtype2 == 0x2E then
						self.PlacedWards[objectID] = {x = objectX2, y = objectY2, z = objectZ2, visionRange = 1100, spawnTime = Helper:GetTime(), duration = 120000, vanga = 1 }	-- GREATER TOTEM
					elseif wardtype2 == 0xAE then
						self.PlacedWards[objectID] = {x = objectX2, y = objectY2, z = objectZ2, visionRange = 1100, spawnTime = Helper:GetTime(), duration = 180000, vanga = 1 }	-- GREATER STEALTH TOTEM
					elseif wardtype2 == 0xEE then
						self.PlacedWards[objectID] = {x = objectX2, y = objectY2, z = objectZ2, visionRange = 1100, spawnTime = Helper:GetTime(), duration = 180000, vanga = 1 }	-- WRIGGLES LANTERN
					elseif (wardtype==8 or wardtype2==0x7E) then
						self.PlacedWards[objectID] = {x = objectX2, y = objectY2, z = objectZ2, visionRange = 1100, spawnTime = Helper:GetTime(), duration = 180000, vanga = 1 } -- VISION
					else
						self.PlacedWards[objectID] = {x = objectX2, y = objectY2, z = objectZ2, visionRange = 1100, spawnTime = Helper:GetTime(), duration = 180000, vanga = 1 }
					end
					end
				end
				p.pos = 1
			end

			function _Wards:_OnTick()
				if self.LastWardAttacked then
					if Helper:GetTime() > self.LastWardAttacked.Time + 100 and self.LastWardAttacked.LastAttack == Orbwalker.LastAttack then
						for i, Ward in pairs(self.EnemyWards) do
							if Ward == self.LastWardAttacked.Object then
								table.remove(self.EnemyWards, i)
								self.LastWardAttacked = nil
								return
							end
						end
					end
				end
			end

			function _Wards:_OnProcessSpell(Unit, Spell)
				if Data:IsWardSpell(Spell) then
					if Unit.team ~= myHero.team then
						table.insert(self.IncomingWards, Spell.endPos)
					else
						table.insert(self.AllyIncomingWards, Spell.endPos)
					end
				end
			end

			function _Wards:_OnCreateObj(Obj)
				if Obj and Data:IsWard(Obj) then
					for i, Inc in pairs(self.IncomingWards) do
						if Helper:GetDistance(Inc, Obj) < 50 then
							table.insert(self.EnemyWards, Obj)
							table.remove(self.IncomingWards, i)
							return
						end
					end
					for i, Inc in pairs(self.PlacedWards) do
						if Helper:GetDistance(Inc, Obj) < 50 then
							table.remove(self.AllyIncomingWards, i)
							return
						end
					end
					-- if VIP_USER then
					table.insert(self.EnemyWards, Obj)
					-- end
				end
			end

			function _Wards:PreAttack(Target)
				if not self.LastWardAttacked then
					for _, Ward in pairs(self.EnemyWards) do
						if Ward == Target then
							self.LastWardAttacked = {Time = Helper:GetTime(), LastAttack = Orbwalker.LastAttack, Object = Ward}
						end
					end
				end
			end

			function _Wards:_OnDeleteObj(Obj)
				for i, Ward in pairs(self.EnemyWards) do
					if Obj == Ward then
						table.remove(self.EnemyWards, i)
					end
				end
			end

			function _Wards:GetAttackableWard()
				for _, Ward in pairs(self.EnemyWards) do
					if Helper:GetDistance(Ward) < MyHero.TrueRange and Ward.visible and not Ward.dead then
						return Ward
					end
				end
			end

			--[[
			_Keys Class
			]]

			class '_Keys' Keys = nil

			function _Keys:__init()
				self.KEYS_KEY = 0
				self.KEYS_MENUKEY = 1
				self.AutoCarry = false
				self.MixedMode = false
				self.LastHit = false
				self.LaneClear = false
				self.AutoCarryKeys = {}
				self.MixedModeKeys = {}
				self.LastHitKeys = {}
				self.LaneClearKeys = {}
				self.LMouseDown = false
				self.AutoCarryKeyDown = false
				self.MixedModeKeyDown = false
				self.LaneClearKeyDown = false
				self.LastHitKeyDown = false
				Keys = self

				AddTickCallback(function() self:_OnTick() end)
				AddMsgCallback(function(Msg, Key) self:_OnWndMsg(Msg, Key) end)
			end

			function _Keys:_OnTick()
				self.AutoCarry = self:IsKeyEnabled(self.AutoCarryKeys)
				self.MixedMode = self:IsKeyEnabled(self.MixedModeKeys)
				self.LastHit = self:IsKeyEnabled(self.LastHitKeys)
				self.LaneClear = self:IsKeyEnabled(self.LaneClearKeys)
				self:ModeKeyPressed()
			end

			function _Keys:ModeKeyPressed()
				if self.AutoCarryKeyDown and not AutoCarryMenu.Active and not AutoCarryMenu.Toggle then
					self:EnableMode(MODE_AUTOCARRY)
				elseif self.MixedModeKeyDown and not MixedModeMenu.Active and not MixedModeMenu.Toggle then
					self:EnableMode(MODE_MIXEDMODE)
				elseif self.LaneClearKeyDown and not LaneClearMenu.Active and not LaneClearMenu.Toggle then
					self:EnableMode(MODE_LANECLEAR)
				elseif self.LastHitKeyDown and not LastHitMenu.Active and not LastHitMenu.Toggle then
					self:EnableMode(MODE_LASTHIT)
				end
			end

			function _Keys:EnableMode(Mode)
				AutoCarryMenu.Active = (Mode == MODE_AUTOCARRY and true or false)
				MixedModeMenu.Active = (Mode == MODE_MIXEDMODE and true or false)
				LaneClearMenu.Active = (Mode == MODE_LANECLEAR and true or false)
				LastHitMenu.Active = (Mode == MODE_LASTHIT and true or false)
			end

			function _Keys:_OnWndMsg(Msg, Key)
				if Msg == WM_LBUTTONDOWN then
					self.LMouseDown = true
				elseif Msg == WM_LBUTTONUP then
					self.LMouseDown = false
				elseif Msg == KEY_DOWN then
					if Key == AutoCarryMenu._param[5].key then
						self.AutoCarryKeyDown = true
					elseif Key == MixedModeMenu._param[3].key then
						self.MixedModeKeyDown = true
					elseif Key == LaneClearMenu._param[3].key then
						self.LaneClearKeyDown = true
					elseif Key == LastHitMenu._param[3].key then
						self.LastHitKeyDown = true
					end
				elseif Msg == KEY_UP then
					if Key == AutoCarryMenu._param[5].key then
						self.AutoCarryKeyDown = false
					elseif Key == MixedModeMenu._param[3].key then
						self.MixedModeKeyDown = false
					elseif Key == LaneClearMenu._param[3].key then
						self.LaneClearKeyDown = false
					elseif Key == LastHitMenu._param[3].key then
						self.LastHitKeyDown = false
					end
				end
			end

			function _Keys:IsKeyEnabled(List)
				for _, Key in pairs(List) do
					if Key.Type == self.KEYS_KEY then
						if IsKeyDown(Key.Key) then
							return true
						end
					elseif Key.Type == self.KEYS_MENUKEY then
						if Key.Menu[Key.Param] then
							return true
						end
					end
				end

				if List == self.AutoCarryKeys and AutoCarryMenu and AutoCarryMenu.LeftClick and self.LMouseDown then
					return true
				end

				return false
			end

			function _Keys:RegisterMenuKey(Menu, Param, Mode)
				local MenuKey = _MenuKey(Menu, Param)
				self:Insert(MenuKey, Mode)
			end

			function _Keys:RegisterKey(key, Mode)
				local Key = _Key(key)
				self:Insert(Key, Mode)
			end

			function _Keys:UnregisterKey(_Key, Mode)
				for i, Key in pairs(self:GetKeyList(Mode)) do
					if Key.Key == _Key then
						table.remove(self:GetKeyList(Mode), i)
					end
				end
			end

			function _Keys:Insert(Key, Mode)
				if Mode == MODE_AUTOCARRY then
					table.insert(self.AutoCarryKeys, Key)
				elseif Mode == MODE_MIXEDMODE then
					table.insert(self.MixedModeKeys, Key)
				elseif Mode == MODE_LASTHIT then
					table.insert(self.LastHitKeys, Key)
				elseif Mode == MODE_LANECLEAR then
					table.insert(self.LaneClearKeys, Key)
				end
			end

			function _Keys:GetKeyList(Mode)
				if Mode == MODE_AUTOCARRY then
					return self.AutoCarryKeys
				elseif Mode == MODE_MIXEDMODE then
					return self.MixedModeKeys
				elseif Mode == MODE_LASTHIT then
					return self.LastHitKeys
				elseif Mode == MODE_LANECLEAR then
					return self.LaneClearKeys
				end
			end


			--[[
			Key Class
			]]

			class '_Key'

			function _Key:__init(key)
				self.Key = key
				self.Type = Keys.KEYS_KEY
			end

			--[[
			MenuKey Class
			]]

			class '_MenuKey'

			function _MenuKey:__init(menu, param)
				self.Menu = menu
				self.Param = param
				self.Type = Keys.KEYS_MENUKEY
			end

			--[[
			MinionDraw Class
			]]

			class '_MinionDraw' MinionDraw = nil

			function _MinionDraw:__init()
				AddDrawCallback(function() self:_OnDraw() end)
			end

			function _MinionDraw:_OnDraw()
				if MinionHealthMenu.Enable then
					local max = MinionHealthMenu.MaxDraw
					local i = 1
					for _, Minion in ipairs(Minions.EnemyMinions.objects) do
						if i <= max then
							if Orbwalker:CanOrbwalkTargetCustomRange(Minion, myHero.range + 500) then
								self:DoDraw(Minion)
								i = i + 1
							end
						end
					end

					if ValidTarget(Minions.KillableMinion) then
						--self:DrawKillable(Minions.KillableMinion)
					elseif ValidTarget(Minions.AlmostKillable) then
						--self:DrawOutline(Minions.AlmostKillable, false)
					end
				end
			end

			function _MinionDraw:DoDraw(minion)
				if minion.charName == "TestCubeRender" or minion.charName == "TT_Buffplat_R" or minion.charName == "TT_Buffplat_L" then return end

				local pos = GetUnitHPBarPos(minion)
				local myDamage = Minions:MyDamage(minion)
				local hitsRemaining = math.ceil(minion.maxHealth / myDamage)
				local barCount = math.ceil(minion.health / myDamage)
				--local barSize = 100 / (minion.maxHealth / 62)
				local barSize = myDamage / (minion.maxHealth / 62)

				local offset = 0
				for i = 1, barCount do
					offset = offset + barSize
					if offset < 62 then
						self:DrawTickerBar(pos.x - 32 + offset, pos.y, 2, 4, ARGB(255, 0, 0, 0))
					end
				end

			end

			function _MinionDraw:DrawTickerBar(x, y, w, h, colour)
				local Points = {}
				Points[1] = D3DXVECTOR2(math.floor(x), math.floor(y))
				Points[2] = D3DXVECTOR2(math.floor(x + w), math.floor(y))
				DrawLines2(Points, math.floor(h), colour)
			end

			function _MinionDraw:DrawOutline(minion, killable)
				local pos = GetUnitHPBarPos(minion)
				local colour = killable and ARGB(255, 0, 255, 0) or ARGB(255, 255, 0, 0)
				self:DrawTickerBar(pos.x - 32, pos.y - 3, 64, 1, colour)
				self:DrawTickerBar(pos.x - 32, pos.y + 2, 64, 1, colour)
				self:DrawTickerBar(pos.x - 32, pos.y, 1, 5, colour)
				self:DrawTickerBar(pos.x - 32 + 63, pos.y, 1, 5, colour)
			end

			function _MinionDraw:DrawKillable(minion)
				local pos = GetUnitHPBarPos(minion)
				pos.x = pos.x - 32

				DrawLine(pos.x, pos.y-1, pos.x + 62, pos.y-1, 3, ARGB(255, 0, 255, 0))
			end

			--[[
			_Streaming Class
			]]

			class '_Streaming'

			function _Streaming:__init()
				self.Save = GetSave("SidasAutoCarry")

				AddTickCallback(function()self:_OnTick() end)
				AddMsgCallback(function(msg, key)self:_OnWndMsg(msg, key) end)

				if self.Save.StreamingMode then
					self:EnableStreaming()
				else
					self:DisableStreaming()
				end
			end

			function _Streaming:_OnTick()
				if self.StreamEnabled then
					self:EnableStreaming()
				end
			end

			function _Streaming:_OnWndMsg(msg, key)
				if msg == KEY_DOWN and key == 118 then
					if not self.StreamEnabled then
						self:EnableStreaming()
					else
						self:DisableStreaming()
					end
				end
			end

			function _Streaming:EnableStreaming()
				self.Save.StreamingMode = true
				self.StreamEnabled = true
				if not self.ChatTimeout then
					self.ChatTimeout = os.clock() + 3
				elseif os.clock() < self.ChatTimeout then
					self:DisableOverlay()
					for i = 0, 15 do
						PrintChat("")
					end
				else
					_G.PrintChat = function() end
				end
			end

			function _Streaming:DisableStreaming()
				self.Save.StreamingMode = false
				self.StreamEnabled = false
				if self.ChatTimeout then
					EnableOverlay()
					self.ChatTimeout = nil
				end
			end

			function _Streaming:CreateMenu()
				self.StreamingMenu = scriptConfig("Sida's Auto Carry: Streaming", "sidasacstreaming")
				self.StreamingMenu:addParam("Show", "Show Click Marker", SCRIPT_PARAM_LIST, 2, { "Stream Mode", "Always", "Never"})
				self.StreamingMenu:addParam("Colour", "Click Marker Colour", SCRIPT_PARAM_LIST, 1, { "Green", "Red", "Smart"})
				self.StreamingMenu:addParam("MinRand", "Minimum Time Between Clicks", SCRIPT_PARAM_SLICE, 150, 0, 1000, 0)
				self.StreamingMenu:addParam("MaxRand", "Maximum Time Between Clicks", SCRIPT_PARAM_SLICE, 650, 0, 1000, 0)
				self.StreamingMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				self.StreamingMenu:addParam("sep", "Toggle Streaming Mode with F7", SCRIPT_PARAM_INFO, "")
				self.MenuCreated = true
			end

			function _Streaming:OnMove()
				if self.MenuCreated then
					-- local func = self.StreamingMenu.Colour == 1 and ShowGreenClick or self.StreamingMenu.Colour == 2 and ShowRedClick or function() self:Smart() end
					-- if self.StreamingMenu.Show == 2 or (self.StreamingMenu.Show == 1 and self.ChatTimeout) then
					-- 	if not self.NextClick or os.clock() * 1000 > self.NextClick and not (ConfigurationMenu.HoldZone and GetDistance(mousePos) < 70) then
					-- 		func(mousePos)
					-- 		self.NextClick = os.clock() * 1000 + math.random(self.StreamingMenu.MinRand, self.StreamingMenu.MaxRand)
					-- 	end
					-- end
				end
			end

			function _Streaming:Smart()
				if (Keys.MixedMode or Keys.AutoCarry) and Crosshair:GetTarget() then
					ShowRedClick(mousePos)
					self.Red = false
				elseif (Keys.LastHit or Keys.LaneClear or Keys.MixedMode) and ValidTarget(Minions.KillableMinion) then
					ShowRedClick(mousePos)
					self.Red = false
				elseif self.Red then
					ShowRedClick(mousePos)
					self.Red = false
				else
					ShowGreenClick(mousePos)
				end
			end

			function _Streaming:DisableOverlay()
				_G.DrawText,
				_G.PrintFloatText,
				_G.DrawLine,
				_G.DrawArrow,
				_G.DrawCircle,
				_G.DrawRectangle,
				_G.DrawLines,
				_G.DrawLines2 = function() end,
					function() end,
					function() end,
					function() end,
					function() end,
					function() end,
					function() end,
					function() end
			end

			Streaming = _Streaming()

			--[[
			_MenuManager Class
			]]

			class '_MenuManager' MenuManager = nil

			function _MenuManager:__init()
				self.AutoCarry = false
				self.MixedMode = false
				self.LastHit = false
				self.LaneClear = false
				self.LaneFreeze = false
				self.LastSaveTick = 0

				AddTickCallback(function() self:OnTick() end)
				AddMsgCallback(function(msg, key) self:OnWndMsg(msg, key) end)
				MenuManager = self


				--[[ Setup Menu]]
				ModesMenu = scriptConfig("Sida's Auto Carry: Setup", "sidasacsetup")
				ModesMenu:addSubMenu("Auto Carry Mode", "sidasacautocarrysub")
				ModesMenu:addSubMenu("Last Hit Mode", "sidasaclasthitsub")
				ModesMenu:addSubMenu("Mixed Mode", "sidasacmixedmodesub")
				ModesMenu:addSubMenu("Lane Clear Mode", "sidasaclaneclearsub")
				AutoCarryMenu = ModesMenu.sidasacautocarrysub
				LastHitMenu = ModesMenu.sidasaclasthitsub
				MixedModeMenu = ModesMenu.sidasacmixedmodesub
				LaneClearMenu = ModesMenu.sidasaclaneclearsub

				ConfigurationMenu = scriptConfig("Sida's Auto Carry: Configuration", "sidasacconfigsub")
				ConfigurationMenu:addSubMenu("Drawing", "sidasacdrawingsub")
				ConfigurationMenu:addSubMenu("Enemy AA Range Circles", "sidasacenemyrangesub")
				ConfigurationMenu:addSubMenu("Minion Health Bars", "sidasacminionhealthsub")
				ConfigurationMenu:addSubMenu("Melee Config", "sidasacmeleesub")
				ConfigurationMenu:addSubMenu("Advanced", "sidasacadvancedsub")
				ConfigurationMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				ConfigurationMenu:addParam("SupportMode", "Support Mode", SCRIPT_PARAM_ONOFF, false)
				ConfigurationMenu:addParam("ToggleRightClickDisable", "Disable Toggle Mode On Right Click", SCRIPT_PARAM_ONOFF, true)
				ConfigurationMenu:addParam("HoldZone", "Mouse Over Hero To Stop Move", SCRIPT_PARAM_ONOFF, false)
				DrawingMenu = ConfigurationMenu.sidasacdrawingsub
				EnemyRangeMenu = ConfigurationMenu.sidasacenemyrangesub
				MinionHealthMenu = ConfigurationMenu.sidasacminionhealthsub
				MeleeMenu = ConfigurationMenu.sidasacmeleesub
				UpdateMenu = ConfigurationMenu.sidasacupdatesub
				AdvancedMenu = ConfigurationMenu.sidasacadvancedsub
				if ConfigurationMenu.SupportMode then
					ConfigurationMenu.SupportMode = false
				end

				if #Skills.SkillsList > 0 then
					SkillsMenu = scriptConfig("Sida's Auto Carry: Skills", "sidasacskills")
					for _, Skill in pairs(Skills.SkillsList) do
						SkillsMenu:addSubMenu(Skill.DisplayName, Skill.RawName)
						SkillsMenu[Skill.RawName]:addParam("AutoCarry", "Use In Auto Carry", SCRIPT_PARAM_ONOFF, false)
						SkillsMenu[Skill.RawName]:addParam("MixedMode", "Use In Mixed Mode", SCRIPT_PARAM_ONOFF, false)
						SkillsMenu[Skill.RawName]:addParam("LaneClear", "Use In Lane Clear", SCRIPT_PARAM_ONOFF, false)
						SkillsMenu[Skill.RawName]:addParam("Killsteal", "Killsteal", SCRIPT_PARAM_ONOFF, false)
					end
				end


				-- Farm Menu
				FarmMenu = scriptConfig("Sida's Auto Carry: Farming", "sidasfarming")
				FarmMenu:addSubMenu("Masteries", "sidasacsubmasteries")
				--FarmMenu:addSubMenu("Last Hitting Skills (not working)", "sidasacsublasthitskill")
				FarmMenu:addSubMenu("Lane Pushing Skills", "sidasacsublanepushskill")
				FarmMenu:addSubMenu("Damage Prediction Settings", "sidasacpredictionfarm")
				FarmMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				FarmMenu:addParam("LaneFreeze", "Lane Freeze", SCRIPT_PARAM_ONKEYTOGGLE, false, 112)
				MasteryMenu = FarmMenu.sidasacsubmasteries
				--LastHitSkillsMenu = FarmMenu.sidasacsublasthitskill
				LaneClearSkillsMenu = FarmMenu.sidasacsublanepushskill
				DamagePredictionMenu = FarmMenu.sidasacpredictionfarm

				-- Lane Clear Settings
				DamagePredictionMenu:addParam("laneClearType", "Lane Clear Method", SCRIPT_PARAM_LIST, 1, { "Default", "Method 2", "Method 3"})


				-- Masteries
				MasteryMenu:addParam("Butcher", "Butcher", SCRIPT_PARAM_ONOFF, false)
				MasteryMenu:addParam("ArcaneBlade", "Arcane Blade", SCRIPT_PARAM_ONOFF, false)
				MasteryMenu:addParam("Havoc", "Havoc", SCRIPT_PARAM_ONOFF, false)
				MasteryMenu:addParam("DoubleEdgedSword", "Double-Edged Sword", SCRIPT_PARAM_ONOFF, false)
				MasteryMenu:addParam("DevastatingStrikes", "Devastating Strike", SCRIPT_PARAM_SLICE, 0, 0, 3, 0)

				MasteryMenu.Butcher = self:LoadChampData("Butcher") or false
				MasteryMenu.ArcaneBlade = self:LoadChampData("ArcaneBlade") or false
				MasteryMenu.Havoc = self:LoadChampData("Havoc") or false
				MasteryMenu.DoubleEdgedSword = self:LoadChampData("DoubleEdgedSword") or false
				MasteryMenu.DevastatingStrikes = self:LoadChampData("DevastatingStrikes") or 0

				-- Last Hit Skills
				-- local _Skills = Skills:GetLastHitSkills()
				-- if #_Skills > 0 then
				-- 	LastHitSkillsMenu:addParam("sep", "Secure Last Hits With:", SCRIPT_PARAM_INFO, "")
				-- 	for _, Skill in pairs(Skills:GetLastHitSkills()) do
				-- 		LastHitSkillsMenu:addParam("FarmSkill"..Skill.RawName, Skill.DisplayName, SCRIPT_PARAM_ONOFF, false)
				-- 	end
				-- 	LastHitSkillsMenu:addParam("MinMana", "Min Mana %", SCRIPT_PARAM_SLICE, 30, 0, 100, 0)
				-- else
				-- 	LastHitSkillsMenu:addParam("sep", "No supported "..myHero.charName.." skills.", SCRIPT_PARAM_INFO, "")
				-- end

				-- Lane Clear Skills
				local _Skills = Skills:GetLastHitSkills()
				if #_Skills > 0 then
					LaneClearSkillsMenu:addParam("sep", "Push Lane With:", SCRIPT_PARAM_INFO, "")
					for _, Skill in pairs(Skills:GetLastHitSkills()) do
						LaneClearSkillsMenu:addParam("FarmSkill"..Skill.RawName, Skill.DisplayName, SCRIPT_PARAM_ONOFF, false)
					end
					LaneClearSkillsMenu:addParam("MinMana", "Min Mana %", SCRIPT_PARAM_SLICE, 30, 0, 100, 0)
				else
					LaneClearSkillsMenu:addParam("sep", "No supported "..myHero.charName.." skills.", SCRIPT_PARAM_INFO, "")
				end

				--[[ Auto Carry Menu ]]

				--AutoCarryMenu = scriptConfig("Sida's Auto Carry: Auto Carry", "sidasacautocarry")
				AutoCarryMenu:addParam("sep", "-- Settings--", SCRIPT_PARAM_INFO, "")
				AutoCarryMenu:addParam("LeftClick", "Left Click Mode", SCRIPT_PARAM_ONOFF, false)
				AutoCarryMenu:addParam("Toggle", "Toggle mode (requires reload)", SCRIPT_PARAM_ONOFF, false)
				AutoCarryMenu:addParam("Active", "Auto Carry", AutoCarryMenu.Toggle and SCRIPT_PARAM_ONKEYTOGGLE or SCRIPT_PARAM_ONKEYDOWN, false, 32)
				Keys:RegisterMenuKey(AutoCarryMenu, "Active", MODE_AUTOCARRY)

				AutoCarryMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				AutoCarryMenu:addParam("sep", "-- Items --", SCRIPT_PARAM_INFO, "")
				for _, Item in pairs(Items.ItemList) do
					AutoCarryMenu:addParam(Item.RawName.."AutoCarry", "Use "..Item.Name, SCRIPT_PARAM_ONOFF, true)
				end
				AutoCarryMenu:addParam("botrkSave", "Save BotRK for max heal", SCRIPT_PARAM_ONOFF, true)
				AutoCarryMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				AutoCarryMenu:addParam("sep", "-- Moving/Attacking --", SCRIPT_PARAM_INFO, "")
				AutoCarryMenu:addParam("Movement", "Movement Enabled", SCRIPT_PARAM_ONOFF, true)
				AutoCarryMenu:addParam("Attacks", "Attacks Enabled", SCRIPT_PARAM_ONOFF, true)


				--[[ Last Hit Menu ]]

				--LastHitMenu = scriptConfig("Sida's Auto Carry: Last Hit", "sidasaclasthit")
				LastHitMenu:addParam("sep", "-- Settings--", SCRIPT_PARAM_INFO, "")
				LastHitMenu:addParam("Toggle", "Toggle mode (requires reload)", SCRIPT_PARAM_ONOFF, false)
				LastHitMenu:addParam("Active", "Last Hit", LastHitMenu.Toggle and SCRIPT_PARAM_ONKEYTOGGLE or SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
				LastHitMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				LastHitMenu:addParam("sep", "-- Moving/Attacking --", SCRIPT_PARAM_INFO, "")
				LastHitMenu:addParam("Movement", "Movement Enabled", SCRIPT_PARAM_ONOFF, true)
				LastHitMenu:addParam("Attacks", "Attacks Enabled", SCRIPT_PARAM_ONOFF, true)
				Keys:RegisterMenuKey(LastHitMenu, "Active", MODE_LASTHIT)


				--[[ Mixed Mode Menu ]]

				--MixedModeMenu = scriptConfig("Sida's Auto Carry: Mixed Mode", "sidasacmixedmode")
				MixedModeMenu:addParam("sep", "-- Settings--", SCRIPT_PARAM_INFO, "")
				MixedModeMenu:addParam("Toggle", "Toggle mode (requires reload)", SCRIPT_PARAM_ONOFF, false)
				MixedModeMenu:addParam("Active", "Mixed Mode", MixedModeMenu.Toggle and SCRIPT_PARAM_ONKEYTOGGLE or SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
				MixedModeMenu:addParam("MinionPriority", "Prioritise Last Hit Over Harass", SCRIPT_PARAM_ONOFF, true)
				Keys:RegisterMenuKey(MixedModeMenu, "Active", MODE_MIXEDMODE)

				MixedModeMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				MixedModeMenu:addParam("sep", "-- Items (Against Champions Only) --", SCRIPT_PARAM_INFO, "")
				for _, Item in pairs(Items.ItemList) do
					MixedModeMenu:addParam(Item.RawName.."MixedMode", "Use "..Item.Name, SCRIPT_PARAM_ONOFF, true)
				end
				MixedModeMenu:addParam("botrkSave", "Save BotRK for max heal", SCRIPT_PARAM_ONOFF, true)
				MixedModeMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				MixedModeMenu:addParam("sep", "-- Moving/Attacking --", SCRIPT_PARAM_INFO, "")
				MixedModeMenu:addParam("Movement", "Movement Enabled", SCRIPT_PARAM_ONOFF, true)
				MixedModeMenu:addParam("Attacks", "Attacks Enabled", SCRIPT_PARAM_ONOFF, true)


				--[[ Lane Clear Menu ]]

				--LaneClearMenu = scriptConfig("Sida's Auto Carry: Lane Clear", "sidasaclaneclear")
				LaneClearMenu:addParam("sep", "-- Settings--", SCRIPT_PARAM_INFO, "")
				LaneClearMenu:addParam("Toggle", "Toggle mode (requires reload)", SCRIPT_PARAM_ONOFF, false)
				LaneClearMenu:addParam("Active", "Lane Clear", LaneClearMenu.Toggle and SCRIPT_PARAM_ONKEYTOGGLE or SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
				LaneClearMenu:addParam("AttackEnemies", "Attack Enemies", SCRIPT_PARAM_ONOFF, true)
				LaneClearMenu:addParam("MinionPriority", "Prioritise Last Hit Over Harass", SCRIPT_PARAM_ONOFF, true)
				Keys:RegisterMenuKey(LaneClearMenu, "Active", MODE_LANECLEAR)

				LaneClearMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				LaneClearMenu:addParam("sep", "-- Items (Against Champions Only) --", SCRIPT_PARAM_INFO, "")
				for _, Item in pairs(Items.ItemList) do
					LaneClearMenu:addParam(Item.RawName.."LaneClear", "Use "..Item.Name, SCRIPT_PARAM_ONOFF, true)
				end
				LaneClearMenu:addParam("botrkSave", "Save BotRK for max heal", SCRIPT_PARAM_ONOFF, true)
				LaneClearMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				LaneClearMenu:addParam("sep", "-- Moving/Attacking --", SCRIPT_PARAM_INFO, "")
				LaneClearMenu:addParam("Movement", "Movement Enabled", SCRIPT_PARAM_ONOFF, true)
				LaneClearMenu:addParam("Attacks", "Attacks Enabled", SCRIPT_PARAM_ONOFF, true)

				--[[ Drawing Menu ]]
				--DrawingMenu = scriptConfig("Sida's Auto Carry: Drawing", "sidasacdrawing")
				DrawingMenu:addParam("RangeCircle", "Champion Range Circle", SCRIPT_PARAM_ONOFF, true)
				DrawingMenu:addParam("RangeCircleColour", "Colour", SCRIPT_PARAM_COLOR, {255, 0, 189, 22})
				DrawingMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				DrawingMenu:addParam("MeleeSticky", "Stick To Target Range (Melee Only)", SCRIPT_PARAM_ONOFF, true)
				DrawingMenu:addParam("MeleeStickyColour", "Stick To Target Colour", SCRIPT_PARAM_COLOR, {183, 0, 26, 173})
				DrawingMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				DrawingMenu:addParam("LowFPSCir", "Use Low FPS Circles", SCRIPT_PARAM_ONOFF, true)

				MeleeMenu:addParam("MeleeStickyRange", "Stick To Target Range (Melee Only)", SCRIPT_PARAM_SLICE, 0, 0, 300, 0)

				-- Advanced menu
				AdvancedMenu:addParam("FarmOffsetType", "Last Hit Adjustment:", SCRIPT_PARAM_LIST, 1, { "None", "  Last Hit Earlier  ", "  Last Hit Later  "})
				AdvancedMenu:addParam("FarmOffsetAmount", "Adjustment Amount:", SCRIPT_PARAM_SLICE, 0, 0, 150, 0)
				AdvancedMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				AdvancedMenu:addParam("WindUpType", "Animation Cancel Adjustment:", SCRIPT_PARAM_LIST, 1, { "None", "  Cancel Earlier  ", "  Cancel Later  "})
				AdvancedMenu:addParam("WindUpAmount", "Adjustment Amount:", SCRIPT_PARAM_SLICE, 0, 0, 150, 0)

				AdvancedMenu.FarmOffsetType = self:LoadChampData("FarmOffsetType") or 1
				AdvancedMenu.FarmOffsetAmount = self:LoadChampData("FarmOffsetAmount") or 0
				if AdvancedMenu.FarmOffsetAmount < 0 then AdvancedMenu.FarmOffsetAmount = AdvancedMenu.FarmOffsetAmount * -1 end
				AdvancedMenu.WindUpType = self:LoadChampData("WindUpType") or 1
				AdvancedMenu.WindUpAmount = self:LoadChampData("WindUpAmount") or 0
				if AdvancedMenu.WindUpAmount < 0 then AdvancedMenu.WindUpAmount = AdvancedMenu.WindUpAmount * -1 end

				-- Enemy circles
				EnemyRangeMenu:addParam("sep", "By Role:", SCRIPT_PARAM_INFO, "")
				EnemyRangeMenu:addParam("Role"..ROLE_AD_CARRY, "    Draw ADC", SCRIPT_PARAM_ONOFF, true)
				EnemyRangeMenu:addParam("Role"..ROLE_AP, "    Draw AP Carry", SCRIPT_PARAM_ONOFF, true)
				EnemyRangeMenu:addParam("Role"..ROLE_SUPPORT, "    Draw Support", SCRIPT_PARAM_ONOFF, true)
				EnemyRangeMenu:addParam("Role"..ROLE_BRUISER, "    Draw Bruiser", SCRIPT_PARAM_ONOFF, true)
				EnemyRangeMenu:addParam("Role"..ROLE_TANK, "    Draw Tank", SCRIPT_PARAM_ONOFF, true)
				EnemyRangeMenu:addParam("sep", "", SCRIPT_PARAM_INFO, "")
				EnemyRangeMenu:addParam("sep", "By Champion:", SCRIPT_PARAM_INFO, "")
				for _, Enemy in pairs(GetEnemyHeroes()) do
					EnemyRangeMenu:addParam(Enemy.charName, "    Draw "..Enemy.charName, SCRIPT_PARAM_ONOFF, false)
				end

				MinionHealthMenu:addParam("Enable", "Modify Minion Health Bars", SCRIPT_PARAM_ONOFF, true)
				MinionHealthMenu:addParam("MaxDraw", "Maximum Health Bars To Modify", SCRIPT_PARAM_SLICE, 10, 1, 30, 0)
				MinionHealthMenu:addParam("MinionArrows", "Draw Last Hit Arrows", SCRIPT_PARAM_ONOFF, true)
				MinionHealthMenu:addParam("MinionMarker", "Always Draw Killable Arrows", SCRIPT_PARAM_ONOFF, false)

				self:DisableAllModes()
			end

			function _MenuManager:OnTick()
				if AutoCarryMenu.Active ~= self.AutoCarry and not self.AutoCarry then
					self:SetToggles(true, false, false, false)
				elseif MixedModeMenu.Active ~= self.MixedMode and not self.MixedMode then
					self:SetToggles(false, true, false, false)
				elseif LastHitMenu.Active ~= self.LastHit and not self.LastHit then
					self:SetToggles(false, false, true, false)
				elseif LaneClearMenu.Active ~= self.LaneClear and not self.LaneClear then
					self:SetToggles(false, false, false, true)
				end

				CustomPermaShow("              Sida's Auto Carry: Reborn", "", true, nil)
				CustomPermaShow("Auto Carry", AutoCarryMenu.Active, true, nil, 1426521024, nil)
				CustomPermaShow("Last Hit", LastHitMenu.Active, true, nil, 1426521024, nil)
				CustomPermaShow("Mixed Mode", MixedModeMenu.Active, true, nil, 1426521024, nil)
				CustomPermaShow("Lane Clear", LaneClearMenu.Active, true, nil, 1426521024, nil)
				CustomPermaShow("Lane Freeze (F1)", "      Active", FarmMenu.LaneFreeze, 1426521024)
				CustomPermaShow("Support Mode", "      Active", ConfigurationMenu.SupportMode, 1426521024)

				self:SaveConfig()
			end

			function _MenuManager:OnWndMsg(msg, key)
				if msg == WM_RBUTTONDOWN then
					local _Menu = self:GetActiveMenu()
					if _Menu and _Menu.Toggle and ConfigurationMenu.ToggleRightClickDisable then
						self:DisableAllModes()
					end
				end
			end

			function _MenuManager:SaveConfig()

				-- Save farm offset for this champ

				if AdvancedMenu.FarmOffsetType == 2 then
					FarmOffset = AdvancedMenu. FarmOffsetAmount * -1
				elseif AdvancedMenu.FarmOffsetType == 3 then
					FarmOffset = AdvancedMenu.FarmOffsetAmount
				else
					FarmOffset = 0
				end
				self:SaveChampData("FarmOffsetAmount", FarmOffset)
				self:SaveChampData("FarmOffsetType", AdvancedMenu.FarmOffsetType)

				-- Save wind up offset for this champ

				if AdvancedMenu.WindUpType == 2 then
					WindOffset = AdvancedMenu.WindUpAmount * -1
				elseif AdvancedMenu.WindUpType == 3 then
					WindOffset = AdvancedMenu.WindUpAmount
				else
					WindOffset = 0
				end
				self:SaveChampData("WindUpAmount", WindOffset)
				self:SaveChampData("WindUpType", AdvancedMenu.WindUpType)

				self:SaveChampData("Butcher", MasteryMenu.Butcher)
				self:SaveChampData("ArcaneBlade", MasteryMenu.ArcaneBlade)
				self:SaveChampData("Havoc", MasteryMenu.Havoc)
				self:SaveChampData("DoubleEdgedSword", MasteryMenu.DoubleEdgedSword)
				self:SaveChampData("DevastatingStrikes", MasteryMenu.DevastatingStrikes)
			end

			function _MenuManager:SaveChampData(param, data)
				local Save = GetSave("SidasAutoCarry")[param]
				if not Save then
					GetSave("SidasAutoCarry")[param] = {}
				end
				GetSave("SidasAutoCarry")[param][myHero.charName] = data
			end

			function _MenuManager:LoadChampData(param)
				return GetSave("SidasAutoCarry")[param] and GetSave("SidasAutoCarry")[param][myHero.charName]
			end

			function _MenuManager:SetToggles(ac, mm, lh, lc)
				AutoCarryMenu.Active, self.AutoCarry = ac, ac
				MixedModeMenu.Active, self.MixedMode = mm, mm
				LastHitMenu.Active, self.LastHit = lh, lh
				LaneClearMenu.Active, self.LaneClear = lc, lc
			end

			function _MenuManager:DisableAllModes()
				AutoCarryMenu.Active = false
				MixedModeMenu.Active = false
				LastHitMenu.Active = false
				LaneClearMenu.Active = false
			end

			function _MenuManager:GetActiveMenu()
				if AutoCarryMenu.Active then
					return AutoCarryMenu
				elseif MixedModeMenu.Active then
					return MixedModeMenu
				elseif LastHitMenu.Active then
					return LastHitMenu
				elseif LaneClearMenu.Active then
					return LaneClearMenu
				end
			end

			--[[
			_Plugins Class
			]]

			class '_Plugins' Plugins = nil

			function _Plugins:__init()
				self.Plugins = {}
				self.RegisteredBonusLastHitDamage = {}
				self.RegisteredPreAttack = {}
				Plugins = self
			end

			function _Plugins:RegisterPlugin(plugin, name)
				if plugin.OnTick then
					AddTickCallback(function() plugin:OnTick() end)
				end
				if plugin.OnDraw then
					AddDrawCallback(function() plugin:OnDraw() end)
				end
				if plugin.OnCreateObj then
					AddCreateObjCallback(function(obj) plugin:OnCreateObj(obj) end)
				end
				if plugin.OnDeleteObj then
					AddDeleteObjCallback(function(obj) plugin:OnDeleteObj(obj) end)
				end
				if plugin.OnLoad then
					plugin:OnLoad()
				end
				if plugin.OnUnload then
					AddUnloadCallback(function() plugin.OnUnload() end)
				end
				if plugin.OnWndMsg then
					AddMsgCallback(function(msg, key) plugin:OnWndMsg(msg, key) end)
				end
				if plugin.OnProcessSpell then
					AddProcessSpellCallback(function(unit, spell) plugin:OnProcessSpell(unit, spell) end)
				end
				if plugin.OnSendChat then
					AddChatCallback(function(text) plugin:OnSendChat(text) end)
				end
				if plugin.OnBugsplat then
					AddBugsplatCallback(function() plugin:OnBugsplat() end)
				end
				if plugin.OnAnimation then
					AddAnimationCallback(function(unit, anim) plugin:OnAnimation(unit, anim) end)
				end
				if plugin.OnSendPacket then
					AddSendPacketCallback(function(packet) plugin:OnSendPacket(packet) end)
				end
				if plugin.OnRecvPacket then
					AddRecvPacketCallback(function(packet) plugin:OnRecvPacket(packet) end)
				end
				if name then
					self.Plugins[name] = scriptConfig("Sida's Auto Carry Plugin: "..name, "sidasacautocarryplugin"..name)
					return self.Plugins[name]
				end
			end

			function _Plugins:RegisterBonusLastHitDamage(func)
				table.insert(self.RegisteredBonusLastHitDamage, func)
			end

			function _Plugins:RegisterPreAttack(func)
				table.insert(self.RegisteredPreAttack, func)
			end

			function _Plugins:RegisterOnAttacked(func)
				RegisterOnAttacked(func)
			end

			function _Plugins:GetProdiction(Key, Range, Speed, Delay, Width, Source, Callback)
				--return ProdictManager.GetInstance():AddProdictionObject(Key, Range, Speed * 1000, Delay / 1000, Width, myHero, Callback)
				return {}
			end

			--[[
			Drawing Class
			]]

			class '_Drawing'

			function _Drawing:__init()
				self.EnemyRoles = {}

				for _, Enemy in pairs(GetEnemyHeroes()) do
					local _role = Data:GetChampionRole(Enemy.charName)
					if _role then
						self.EnemyRoles[Enemy.charName] = _role
					end
				end

				AddDrawCallback(function() self:_OnDraw() end)
			end

			function _Drawing:_OnDraw()

				if DrawingMenu.RangeCircle then
					Helper:DrawCircleObject(myHero, MyHero.TrueRange + 55, Helper:ArgbFromMenu(DrawingMenu.RangeCircleColour))
				end

				-- if Keys.LaneClear or Keys.MixedMode or Keys.LastHit or FarmMenu.MinionMarker then
				-- 	if DrawingMenu.MinionCircle and Minions.KillableMinion then
				-- 		Helper:DrawCircleObject(Minions.KillableMinion, 80, Helper:ArgbFromMenu(DrawingMenu.MinionCircleColour), 6)
				-- 	end

				-- 	if DrawingMenu.AlmostMinionCircle and Minions.AlmostKillable then
				-- 		Helper:DrawCircleObject(Minions.AlmostKillable, 150, Helper:ArgbFromMenu(DrawingMenu.AlmostMinionCircleColour), 4)
				-- 	end
				-- end

				if DrawingMenu.MeleeSticky and MeleeMenu.MeleeStickyRange > 0 and myHero.range < 300 then
					Helper:DrawCircleObject(myHero, MeleeMenu.MeleeStickyRange, Helper:ArgbFromMenu(DrawingMenu.MeleeStickyColour))
				end

				if MinionHealthMenu.MinionArrows then
					if Minions.KillableMinion then
						self:DrawWhiteMinionSprite(Minions.KillableMinion)
					elseif Minions.AlmostKillable then
						self:DrawOrangeMinionSprite(Minions.AlmostKillable)
					end
				end

				for _, Enemy in pairs(GetEnemyHeroes()) do
					if Orbwalker:CanOrbwalkTargetCustomRange(Enemy, 1000) then
						local role = self.EnemyRoles[Enemy.charName]
						if (role and EnemyRangeMenu["Role"..role]) or EnemyRangeMenu[Enemy.charName] then
							local range = Enemy.range + Data:GetGameplayCollisionRadius(Enemy.charName) + Orbwalker:GetScalingRange(Enemy)
							if GetDistance(Enemy) <= range then
								Helper:DrawCircleObject(Enemy, range, ARGB(255, 255, 0, 0) , 4)
							else
								Helper:DrawCircleObject(Enemy, range, ARGB(255, 0, 255, 0) , 1)
							end
						end
					end
				end
			end

			function _Drawing:DrawMinionHealthSprite(Minion, Sprite)
				if ValidTarget(Minion) then
					local pos = GetUnitHPBarPos(Minion)
					if pos and pos.x and pos.y then
						Sprite:Draw(pos.x - 59, pos.y - 16, 255)
					end
				end
			end

			function _Drawing:DrawGreenMinionSprite(Minion)
				if not self.GreenSprite then
					self.GreenSprite = createSprite(SPRITE_PATH.."SidasAutoCarry\\Minion_Green.png")
				end
				self:DrawMinionHealthSprite(Minion, self.GreenSprite)
			end

			function _Drawing:DrawRedMinionSprite(Minion)
				if not self.RedSprite then
					self.RedSprite = createSprite(SPRITE_PATH.."SidasAutoCarry\\Minion_Red.png")
				end
				self:DrawMinionHealthSprite(Minion, self.RedSprite)
			end

			function _Drawing:DrawOrangeMinionSprite(Minion)
				if not self.OrangeSprite then
					self.OrangeSprite = createSprite(SPRITE_PATH.."SidasAutoCarry\\Minion_Orange.png")
				end
				self:DrawMinionHealthSprite(Minion, self.OrangeSprite)
			end

			function _Drawing:DrawWhiteMinionSprite(Minion)
				if not self.WhiteSprite then
					self.WhiteSprite = createSprite(SPRITE_PATH.."SidasAutoCarry\\Minion_White.png")
				end
				self:DrawMinionHealthSprite(Minion, self.WhiteSprite)
			end

			function _Drawing:DrawPinkMinionSprite(Minion)
				if not self.PinkSprite then
					self.PinkSprite = createSprite(SPRITE_PATH.."SidasAutoCarry\\Minion_Pink.png")
				end
				self:DrawMinionHealthSprite(Minion, self.PinkSprite)
			end

			function _Drawing:DrawBlackMinionSprite(Minion)
				if not self.BlackSprite then
					self.BlackSprite = createSprite(SPRITE_PATH.."SidasAutoCarry\\Minion_Black.png")
				end
				self:DrawMinionHealthSprite(Minion, self.BlackSprite)
			end

			function _Drawing:DrawGreyMinionSprite(Minion)
				if not self.GreySprite then
					self.GreySprite = createSprite(SPRITE_PATH.."SidasAutoCarry\\Minion_Grey.png")
				end
				self:DrawMinionHealthSprite(Minion, self.GreySprite)
			end



			--[[
			_Data Class
			]]


			class '_Data' Data = nil

			function _Data:__init()
				self.ResetSpells = {}
				self.SpellAttacks = {}
				self.NoneAttacks = {}
				self.ChampionData = {}
				self.MinionData = {}
				self.JungleData = {}
				self.ItemData = {}
				self.Skills = {}
				self.EnemyHitBoxes = {}
				self.ImmuneEnemies = {}
				self.WardData = {}
				Data = self

				self:__GenerateNoneAttacks()
				self:__GenerateSpellAttacks()
				self:__GenerateResetSpells()
				self:_GenerateMinionData()
				self:_GenerateJungleData()
				self:_GenerateItemData()
				self:__GenerateChampionData()
				self:__GenerateSkillData()
				Data:_GenerateWardData()

				AdvancedCallback:bind("OnGainBuff", function(Unit, Buff) self:OnGainBuff(Unit, Buff) end)
				AdvancedCallback:bind("OnLoseBuff", function(Unit, Buff) self:OnLoseBuff(Unit, Buff) end)

				if GetGameTimer() < self:GetHitBoxLastSavedTime() then
					self:GenerateHitBoxData()
				else
					self:LoadHitBoxData()
				end
			end

			function _Data:OnGainBuff(Unit, Buff)
				if Unit.team ~= myHero.team and (Buff.name == "UndyingRage" or Buff.name == "JudicatorIntervention") then
					self.ImmuneEnemies[Unit.charName] = true
				end
			end

			function _Data:OnLoseBuff(Unit, Buff)
				if Unit.team ~= myHero.team and (Buff.name == "UndyingRage" or Buff.name == "JudicatorIntervention") then
					self.ImmuneEnemies[Unit.charName] = nil
				end
			end

			function _Data:__GenerateResetSpells()
				self:AddResetSpell("Powerfist")
				self:AddResetSpell("DariusNoxianTacticsONH")
				self:AddResetSpell("Takedown")
				self:AddResetSpell("Ricochet")
				self:AddResetSpell("BlindingDart")
				self:AddResetSpell("VayneTumble")
				self:AddResetSpell("JaxEmpowerTwo")
				self:AddResetSpell("MordekaiserMaceOfSpades")
				self:AddResetSpell("SiphoningStrikeNew")
				self:AddResetSpell("RengarQ")
				self:AddResetSpell("MonkeyKingDoubleAttack")
				self:AddResetSpell("YorickSpectral")
				self:AddResetSpell("ViE")
				self:AddResetSpell("GarenSlash3")
				self:AddResetSpell("HecarimRamp")
				self:AddResetSpell("XenZhaoComboTarget")
				self:AddResetSpell("LeonaShieldOfDaybreak")
				self:AddResetSpell("ShyvanaDoubleAttack")
				self:AddResetSpell("shyvanadoubleattackdragon")
				self:AddResetSpell("TalonNoxianDiplomacy")
				self:AddResetSpell("TrundleTrollSmash")
				self:AddResetSpell("VolibearQ")
				self:AddResetSpell("PoppyDevastatingBlow")
				self:AddResetSpell("SivirW")
				self:AddResetSpell("Ricochet")
			end

			function _Data:__GenerateSpellAttacks()
				self:AddSpellAttack("frostarrow")
				self:AddSpellAttack("CaitlynHeadshotMissile")
				self:AddSpellAttack("QuinnWEnhanced")
				self:AddSpellAttack("TrundleQ")
				self:AddSpellAttack("XenZhaoThrust")
				self:AddSpellAttack("XenZhaoThrust2")
				self:AddSpellAttack("XenZhaoThrust3")
				self:AddSpellAttack("GarenSlash2")
				self:AddSpellAttack("RenektonExecute")
				self:AddSpellAttack("RenektonSuperExecute")
				self:AddSpellAttack("KennenMegaProc")
				self:AddSpellAttack("redcardpreattack")
				self:AddSpellAttack("bluecardpreattack")
				self:AddSpellAttack("goldcardpreattack")
				self:AddSpellAttack("MasterYiDoubleStrike")
			end

			function _Data:__GenerateNoneAttacks()
				self:AddNoneAttack("shyvanadoubleattackdragon")
				self:AddNoneAttack("ShyvanaDoubleAttack")
				self:AddNoneAttack("MonkeyKingDoubleAttack")
			end

			function _Data:_GenerateMinionData()
				self:AddMinionData((myHero.team == 100 and "Blue" or "Red").."_Minion_Basic", 400, 0)
				self:AddMinionData((myHero.team == 100 and "Blue" or "Red").."_Minion_Caster", 484, 0.65)
				self:AddMinionData((myHero.team == 100 and "Blue" or "Red").."_Minion_Wizard", 484, 0.65)
				self:AddMinionData((myHero.team == 100 and "Blue" or "Red").."_Minion_MechCannon", 365, 1.2)
				self:AddMinionData("obj_AI_Turret", 150, 1.2)
			end

			function _Data:_GenerateJungleData()
				self:AddJungleMonster("Worm12.1.1", 		1)		-- Baron
				self:AddJungleMonster("Dragon6.1.1", 		1)		-- Dragon
				self:AddJungleMonster("AncientGolem1.1.1", 	1)		-- Blue Buff
				self:AddJungleMonster("AncientGolem7.1.1", 	1)		-- Blue Buff
				self:AddJungleMonster("YoungLizard1.1.2", 	2)		-- Blue Buff Add
				self:AddJungleMonster("YoungLizard7.1.3", 	2)		-- Blue Buff Add
				self:AddJungleMonster("YoungLizard1.1.3", 	2)		-- Blue Buff Add
				self:AddJungleMonster("YoungLizard7.1.2", 	2)		-- Blue Buff Add
				self:AddJungleMonster("LizardElder4.1.1", 	1)		-- Red Buff
				self:AddJungleMonster("LizardElder10.1.1", 	1)		-- Red Buff
				self:AddJungleMonster("YoungLizard4.1.2", 	2)		-- Red Buff Add
				self:AddJungleMonster("YoungLizard4.1.3", 	2)		-- Red Buff Add
				self:AddJungleMonster("YoungLizard10.1.2", 	2)		-- Red Buff Add
				self:AddJungleMonster("YoungLizard10.1.3", 	2)		-- Red Buff Add
				self:AddJungleMonster("GiantWolf2.1.3", 	1)		-- Big Wolf
				self:AddJungleMonster("GiantWolf2.1.1", 	1)		-- Big Wolf
				self:AddJungleMonster("GiantWolf8.1.3", 	1)		-- Big Wolf
				self:AddJungleMonster("GiantWolf8.1.1", 	1)		-- Big Wolf
				self:AddJungleMonster("wolf2.1.1", 			2)		-- Small Wolf
				self:AddJungleMonster("wolf2.1.2", 			2)		-- Small Wolf
				self:AddJungleMonster("wolf8.1.1", 			2)		-- Small Wolf
				self:AddJungleMonster("wolf8.1.2", 			2)		-- Small Wolf
				self:AddJungleMonster("Wolf8.1.3", 			2)		-- Small Wolf
				self:AddJungleMonster("Wolf8.1.2", 			2)		-- Small Wolf
				self:AddJungleMonster("Wolf2.1.3", 			2)		-- Small Wolf
				self:AddJungleMonster("Wolf2.1.2", 			2)		-- Small Wolf
				self:AddJungleMonster("Wraith3.1.3", 		1)		-- Big Wraith
				self:AddJungleMonster("Wraith3.1.1", 		1)		-- Big Wraith
				self:AddJungleMonster("Wraith9.1.3", 		1)		-- Big Wraith
				self:AddJungleMonster("Wraith9.1.1", 		1)		-- Big Wraith
				self:AddJungleMonster("LesserWraith3.1.1", 	2)		-- Small Wraith
				self:AddJungleMonster("LesserWraith3.1.3", 	2)		-- Small Wraith
				self:AddJungleMonster("LesserWraith3.1.2", 	2)		-- Small Wraith
				self:AddJungleMonster("LesserWraith3.1.4", 	2)		-- Small Wraith
				self:AddJungleMonster("LesserWraith9.1.1", 	2)		-- Small Wraith
				self:AddJungleMonster("LesserWraith9.1.2", 	2)		-- Small Wraith
				self:AddJungleMonster("LesserWraith9.1.4", 	2)		-- Small Wraith
				self:AddJungleMonster("LesserWraith9.1.3", 	2)		-- Small Wraith
				self:AddJungleMonster("Golem5.1.2", 		1)		-- Big Golem
				self:AddJungleMonster("Golem11.1.2", 		1)		-- Big Golem
				self:AddJungleMonster("SmallGolem5.1.1", 	2)		-- Small Golem
				self:AddJungleMonster("SmallGolem11.1.1", 	2)		-- Small Golem
				self:AddJungleMonster("GreatWraith13.1.1", 	2)		-- Great Wraith
				self:AddJungleMonster("GreatWraith14.1.1", 	2)		-- Great Wraith
			end

			function _Data:_GenerateItemData()
				self:AddItemData("Blade of the Ruined King", 	3153, true, 500)
				self:AddItemData("Bilgewater Cutlass", 			3144, true, 500)
				self:AddItemData("Deathfire Grasp", 			3128, true, 750)
				self:AddItemData("Hextech Gunblade", 			3146, true, 400)
				self:AddItemData("Blackfire Torch", 			3188, true, 750)
				self:AddItemData("Frost Queens Claim", 			3098, true, 750)
				self:AddItemData("Talisman of Ascension", 		3098, false)
				self:AddItemData("Ravenous Hydra", 				3074, false)
				self:AddItemData("Sword of the Divine", 		3131, false)
				self:AddItemData("Tiamat", 						3077, false)
				self:AddItemData("Entropy", 					3184, false)
				self:AddItemData("Youmuu's Ghostblade", 		3142, false)
				self:AddItemData("Muramana", 					3042, false)
				self:AddItemData("Randuins Omen", 				3143, false)
			end
			OBJECT_TYPE_WARD = 0
			OBJECT_TYPE_BOX = 1
			OBJECT_TYPE_TRAP = 2
			function _Data:_GenerateWardData()
				-- charName	   	 -- Name 			--spellName 	    	-- Type			--Range   --Duration
				self:AddWardData("VisionWard",		"VisionWard", 		"visionward", 		OBJECT_TYPE_WARD, 	 1450,		180000)
				self:AddWardData("SightWard",		"SightWard", 		"sightward", 		OBJECT_TYPE_WARD, 	 1450,		180000)
				self:AddWardData("YellowTrinket",	"SightWard", 		"sightward", 		OBJECT_TYPE_WARD, 	 1450,		180000)
				self:AddWardData("SightWard",		"VisionWard", 		"itemghostward", 	OBJECT_TYPE_WARD, 	 1450,		180000)
				self:AddWardData("SightWard",		"VisionWard", 		"itemminiward", 	OBJECT_TYPE_WARD, 	 1450,		60000)
				self:AddWardData("SightWard",		"SightWard", 		"wrigglelantern", 	OBJECT_TYPE_WARD, 	 1450,		180000)
				self:AddWardData("ShacoBox",		"Jack In The Box", 	"jackinthebox", 	OBJECT_TYPE_BOX, 	 300,		60000)
			end

			ROLE_AD_CARRY = 1
			ROLE_AP = 2
			ROLE_SUPPORT = 3
			ROLE_BRUISER = 4
			ROLE_TANK = 5

			function _Data:__GenerateChampionData()
				-- Champion, Projectile Speed,	GameplayCollisionRadius 	Anti-bug delay 			Role
				self:AddChampionData("Aatrox",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Ahri",            1.6,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Akali",           0,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Alistar",         0,   				80,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Amumu",           0,   				55,						0,      		ROLE_TANK)
				self:AddChampionData("Anivia",          1.4,	   			65,						0,      		ROLE_AP)
				self:AddChampionData("Annie",           1,   				55,						0,      		ROLE_AP)
				self:AddChampionData("Ashe",            2,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Blitzcrank",      0,   				80,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Brand",           1.975,  			65,						0,      		ROLE_AP)
				self:AddChampionData("Caitlyn",         2.5,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Cassiopeia",      1.22,   			65,						0,      		ROLE_AP)
				self:AddChampionData("Chogath",         0,   				80,						0,      		ROLE_TANK)
				self:AddChampionData("Corki",           2,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Darius",			0,					80,						0,				ROLE_BRUISER)
				self:AddChampionData("Diana",           0,   				65,						0,      		ROLE_AP)
				self:AddChampionData("DrMundo",         0,   				80,						0,      		ROLE_TANK)
				self:AddChampionData("Draven",          1.4,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Elise",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Evelynn",         0,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Ezreal",          2,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("FiddleSticks",    1.75,   			65,						0,      		ROLE_AP)
				self:AddChampionData("Fiora",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Fizz",            0,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Galio",           0,   				80,						0,      		ROLE_TANK)
				self:AddChampionData("Gangplank",		0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Garen",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Gragas",          0,   				80,						0,      		ROLE_AP)
				self:AddChampionData("Graves",          3,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Hecarim",         0,   				80,						0,      		ROLE_TANK)
				self:AddChampionData("Heimerdinger",    1.4,   				55,						0,      		ROLE_AP)
				self:AddChampionData("Irelia",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Janna",           1.2,   				65,						0,      		ROLE_SUPPORT)
				self:AddChampionData("JarvanIV",		0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Jax",				0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Jayce",           2.2,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Jinx",           	2,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Karma",           1.2,   				65,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Karthus",         1.25,   			65,						0,      		ROLE_AP)
				self:AddChampionData("Kassadin",        0,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Katarina",        0,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Kayle",           1.8,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Kennen",          1.35,   			55,						0,      		ROLE_AP)
				self:AddChampionData("Khazix",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("KogMaw",          1.8,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Leblanc",         1.7,   				65,						0,      		ROLE_AP)
				self:AddChampionData("LeeSin",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Leona",           0,   				65,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Lissandra",       0,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Lucian",          2,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Lulu",            2.5,   				65,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Lux",            	1.55,   			65,						0,      		ROLE_AP)
				self:AddChampionData("Malphite",        0,   				80,						0,      		ROLE_TANK)
				self:AddChampionData("Malzahar",        1.5,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Maokai",          0,   				80,						0,      		ROLE_TANK)
				self:AddChampionData("MasterYi",        0,   				65,						0,      		ROLE_AP)
				self:AddChampionData("MissFortune",     2,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("MonkeyKing",		0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Mordekaiser",     0,   				80,						0,      		ROLE_AP)
				self:AddChampionData("Morgana",         1.6,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Nami",            0,   				65,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Nasus",           0,   				80,						0,      		ROLE_TANK)
				self:AddChampionData("Nautilus",		0,					80,						0,				ROLE_BRUISER)
				self:AddChampionData("Nidalee",         1.7,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Nocturne",		0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Nunu",            0,   				65,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Olaf",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Orianna",         1.4,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Pantheon",        0,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Poppy",			0,					55,						0,				ROLE_BRUISER)
				self:AddChampionData("Quinn",           1.85,   			65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Rammus",          0,   				65,						0,      		ROLE_TANK)
				self:AddChampionData("Renekton",		0,					80,						0,				ROLE_BRUISER)
				self:AddChampionData("Rengar",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Riven",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Rumble",          0,   				80,						0,      		ROLE_AP)
				self:AddChampionData("Ryze",            2.4,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Sejuani",         0,   				80,						0,      		ROLE_TANK)
				self:AddChampionData("Shaco",           0,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Shen",            0,   				65,						0,      		ROLE_TANK)
				self:AddChampionData("Shyvana",			0,					50,						0,				ROLE_BRUISER)
				self:AddChampionData("Singed",          0,   				65,						0,      		ROLE_TANK)
				self:AddChampionData("Sion",            0,   				80,						0,      		ROLE_AP)
				self:AddChampionData("Sivir",           1.4,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Skarner",         0,   				80,						0,      		ROLE_TANK)
				self:AddChampionData("Sona",            1.6,   				65,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Soraka",          1,   				65,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Swain",           1.6,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Syndra",          1.2,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Talon",           0,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Taric",           0,   				65,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Teemo",           1.3,   				55,						0,      		ROLE_AP)
				self:AddChampionData("Thresh",          0,   				55,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Tristana",        2.25,   			55,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Trundle",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Tryndamere",		0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("TwistedFate",     1.5,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Twitch",          2.5,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Udyr",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Urgot",           1.3,   				80,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Varus",           2,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Vayne",           2,   				65,						0,     			ROLE_AD_CARRY)
				self:AddChampionData("Veigar",          1.05,   			55,						0,      		ROLE_AP)
				self:AddChampionData("Velkoz",			1.8,				55,						0,				ROLE_AP)
				self:AddChampionData("Vi",				0,					50,						0,				ROLE_BRUISER)
				self:AddChampionData("Viktor",          2.25,   			65,						0,      		ROLE_AP)
				self:AddChampionData("Vladimir",        1.4,   				65,						0,      		ROLE_AP)
				self:AddChampionData("Volibear",        0,   				80,						0,      		ROLE_TANK)
				self:AddChampionData("Warwick",         0,   				65,						0,      		ROLE_TANK)
				self:AddChampionData("Xerath",          1.2,   				65,						0,      		ROLE_AP)
				self:AddChampionData("XinZhao",			0,					65,						0,				ROLE_BRUISER)
				self:AddChampionData("Yasuo",          	0,   				65,						0,      		ROLE_BRUISER)
				self:AddChampionData("Yorick",          0,   				80,						0,      		ROLE_TANK)
				self:AddChampionData("Zac",             0,   				65,						0,      		ROLE_TANK)
				self:AddChampionData("Zed",             0,   				65,						0,      		ROLE_AD_CARRY)
				self:AddChampionData("Ziggs",           1.5,   				55,						0,      		ROLE_AP)
				self:AddChampionData("Zilean",          1.25,   			65,						0,      		ROLE_SUPPORT)
				self:AddChampionData("Zyra",            1.7,   				65,						0,      		ROLE_AP)
			end

			function _Data:__GenerateSkillData()

				if _G.rebornskillslist then
					for _, skill in pairs(_G.rebornskillslist) do
						if skill[1] == myHero.charName then
							self:AddSkillData(myHero.charName, skill[2], skill[3], skill[4], skill[5], skill[6], skill[7], skill[8], skill[9], skill[10], skill[11], skill[12], skill[13], skill[14])
						end
					end
				end

				if _G.rebornskillslist then return end


				--Name 			  Enabled    Key 	 Range 		Display Name 				Type 			MinMana  AfterAA Require Attack Target 	   Speed 		Delay 	Width 	Collision  	 ResetAA
				self:AddSkillData("Aatrox",		 	true,	 _E,	 1000,	 "E (Blades of Torment)",	SPELL_LINEAR,			0,	 false,	 	false,	 				1.2, 		500,	150,	false, 		 false)
				self:AddSkillData("Ahri",			true,	 _Q,	 880,	 "Q (Orb of Deception)",	SPELL_LINEAR,			0,   false,		false,					1.1,		500,	100,	false,		 false)
				self:AddSkillData("Ahri",			true,	 _E,	 880,	 "E (Orb of Deception)",	SPELL_LINEAR_COL,		0,   false,		false,					1.2,		0.5,	60,		false,		 false)
				self:AddSkillData("Ezreal",		 	true,	 _Q,	 1100,	 "Q (Mystic Shot)",	 		SPELL_LINEAR_COL,		0,	 false,	 	false,	 				2,	 		250,	70,	 	true, 		 false)
				self:AddSkillData("Ezreal",		 	true,	 _W,	 1050,	 "W (Essence Flux)",		SPELL_LINEAR,	 		0,	 false,	 	false,	 				1.6,	 	250,	90,	 	false, 		 false)
				self:AddSkillData("Ezreal",			true, 	 _R, 	 2000,  "R (Trueshot Barrage)",	SPELL_LINEAR,			0,   false, 	false,  				2, 			500, 	160, 	false, 		 false)
				self:AddSkillData("KogMaw",		 	true,	 _Q,	 625,	 "Q (Caustic Spittle)",	 	SPELL_TARGETED,	 		0,	 true,	 	true,	 				1.3,	 	260,	200,	false, 		 false)
				self:AddSkillData("KogMaw",		 	true,	 _W,	 625,	 "W (Bio-Arcane Barrage)",	SPELL_SELF,	 			0,	 false,	 	false,	 				1.3,	 	260,	200,	false, 		 false)
				self:AddSkillData("KogMaw",		 	true,	 _E,	 850,	 "E (Void Ooze)",	 		SPELL_LINEAR,	 		0,	 false,	 	false,	 				1.3,	 	260,	200,	false, 		 false)
				self:AddSkillData("KogMaw",		 	true,	 _R,	 1700,	 "R (Living Artillery)",	SPELL_LINEAR,	 		0,	 false,	 	false,	 				math.huge,	1000,	200,	false, 		 false)
				self:AddSkillData("Sivir",		 	true,	 _Q,	 1000,	 "Q (Boomerang Blade)",	 	SPELL_LINEAR,	 		0,	 false,	 	false,	 				1.33,	 	250,	120,	false, 		 false)
				self:AddSkillData("Sivir",		 	true,	 _W,	 900,	 "W (Ricochet)",	 		SPELL_SELF,	 			0,	 true,	 	true,	 				1,	 		0,	 	200,	false, 		 true)
				self:AddSkillData("Graves",		 	true,	 _Q,	 750,	 "Q (Buck Shot)",	 		SPELL_CONE,	 			0,	 false,	 	false,	 				2,	 		250,	200,	false, 		 false)
				self:AddSkillData("Graves",		 	true,	 _W,	 700,	 "W (Smoke Screen)",	 	SPELL_CIRCLE,	 		0,	 false,	 	false,	 				1400,	 	300,	500,	false, 		 false)
				self:AddSkillData("Graves",		 	true,	 _E,	 580,	 "E (Quick Draw)",	 		SPELL_SELF_AT_MOUSE,	0,	 true,	 	true,	 				1450,	 	250,	200,	false, 		 false)
				self:AddSkillData("Caitlyn",		true,	 _Q,	 1300,	 "Q (Piltover Peacemaker)",	SPELL_LINEAR,			0,	 false,	 	false,	 				2.1,	 	625,	100,	true, 		 false)
				self:AddSkillData("Corki",		 	true,	 _Q,	 600,	 "Q (Phosphorus Bomb)",	 	SPELL_CIRCLE,			0,	 false,	 	false,	 				2,	 		200,	500,	false, 		 false)
				self:AddSkillData("Corki",		 	true,	 _R,	 1225,	 "R (Missile Barrage)",	 	SPELL_LINEAR_COL,		0,	 false,	 	false,	 				2,	 		200,	50,	 	true, 		 false)
				self:AddSkillData("Teemo",		 	true,	 _Q,	 580,	 "Q (Blinding Dart)",	 	SPELL_TARGETED,	 		0,	 false,	 	false,	 				2,	 		0,		200,	false, 		 true)
				self:AddSkillData("TwistedFate",	true,	 _Q,	 1200,	 "Q (Wild Cards)",	 		SPELL_LINEAR,	 		0,	 false,	 	false,	 				1.45,		250,	200,	false, 		 false)
				self:AddSkillData("Vayne",			true,	 _Q,	 750,	 "Q (Tumble)",	 			SPELL_SELF_AT_MOUSE,	0,	 true,	 	true,	 				1.45,		250,	200,	false, 		 true)
				self:AddSkillData("Vayne",			true,	 _R,	 580,	 "R (Final Hour)",	 		SPELL_SELF,	 			0,	 false,	 	true,	 				1.45,		250,	200,	false, 		 false)
				self:AddSkillData("MissFortune",	true,	 _Q,	 650,	 "Q (Double Up)",	 		SPELL_TARGETED,	 		0,	 true,	 	true,	 				1.45,		250,	200,	false, 		 false)
				self:AddSkillData("MissFortune",	true,	 _W,	 580,	 "W (Impure Shots)",	 	SPELL_SELF,	 			0,	 false,	 	true,	 				1.45,		250,	200,	false, 		 false)
				self:AddSkillData("MissFortune",	true,	 _E,	 800,	 "E (Make It Rain)",	 	SPELL_CIRCLE,	 		0,	 false,	 	false,	 				math.huge,	500,	500,	false, 		 false)
				self:AddSkillData("Tristana",		true,	 _Q,	 580,	 "Q (Rapid Fire)",	 		SPELL_SELF,	 			0,	 false,	 	true,	 				1.45,	 	250,	200,	false, 		 false)
				self:AddSkillData("Tristana",		true,	 _E,	 550,	 "E (Explosive Shot)",		SPELL_TARGETED,			0,	 true,	 	false,	 				1.45,	 	250,	200,	false, 		 false)
				self:AddSkillData("Draven",			true,	 _E,	 950,	 "E (Stand Aside)",	 		SPELL_LINEAR,			0,	 false,	 	false,	 				1.37,	 	300,	130,	false, 		 false)
				self:AddSkillData("Kennen",			true,	 _Q,	 1050,	 "Q (Thundering Shuriken)",	SPELL_LINEAR_COL,		0,	 false,	 	false,	 				1.65,	 	180,	80,	 	true, 		 false)
				self:AddSkillData("Ashe",			true,	 _W,	 1200,	 "W (Volley)",	 			SPELL_LINEAR_COL,		0,	 false,	 	false,	 				2,	 		120,	85,	 	true, 		 false)
				self:AddSkillData("Syndra",			true,	 _Q,	 800,	 "Q (Dark Sphere)",	 		SPELL_CIRCLE,	 		0,	 false,	 	false,	 				math.huge,	400,	100,	false, 		 false)
				self:AddSkillData("Jayce",			true,	 _Q,	 1600,	 "Q (Shock Blast)",	 		SPELL_LINEAR_COL,		0,	 false,	 	false,	 				2,	 		350,	90,	 	true, 		 false)
				self:AddSkillData("Nidalee",		true,	 _Q,	 1500,	 "Q (Javelin Toss)",	 	SPELL_LINEAR_COL,		0,	 false,	 	false,	 				1.3,		125,	80,	 	true, 		 false)
				self:AddSkillData("Varus",			true,	 _E,	 925,	 "E (Hail of Arrows)",	 	SPELL_CIRCLE,	 		0,	 false,	 	false,	 				1.75,		240,	235,	false, 		 false)
				self:AddSkillData("Quinn",			true,	 _Q,	 1050,	 "Q (Blinding Assault)",	SPELL_LINEAR_COL,		0,	 false,	 	false,	 				1.55,		220,	90,	 	true, 		 false)
				self:AddSkillData("LeeSin",			true,	 _Q,	 975,	 "Q (Sonic Wave)",	 		SPELL_LINEAR_COL,		0,	 false,	 	false,	 				1.5,		250,	70,	 	true, 		 false)
				self:AddSkillData("Twitch",		 	true,	 _W,	 950,	 "W (Venom Cask)",	 		SPELL_CIRCLE,	 		0,	 false,	 	false,	 				1.4,		250,	275,	false, 		 false)
				self:AddSkillData("Darius",		 	true,	 _W,	 300,	 "W (Crippling Strike)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 true)
				self:AddSkillData("Hecarim",		true,	 _Q,	 300,	 "Q (Rampage)",	 			SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 false)
				self:AddSkillData("Warwick",		true,	 _Q,	 300,	 "Q (Hungering Strike)",	SPELL_TARGETED,	 		0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 false)
				self:AddSkillData("MonkeyKing",		true,	 _Q,	 300,	 "Q (Crushing Blow)",		SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 true)
				self:AddSkillData("Poppy",		 	true,	 _Q,	 300,	 "Q (Devastating Blow)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 true)
				self:AddSkillData("Talon",		 	true,	 _Q,	 300,	 "Q (Noxian Diplomacy)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 false)
				self:AddSkillData("Nautilus",		true,	 _W,	 300,	 "W (Titans Wrath)",	 	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 false)
				self:AddSkillData("Vi",		 		true,	 _E,	 300,	 "E (Excessive Force)",	 	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 true)
				self:AddSkillData("Rengar",		 	true,	 _Q,	 300,	 "Q (Savagery)",	 		SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 false)
				self:AddSkillData("Trundle",		true,	 _Q,	 300,	 "Q (Chomp)",	 			SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 true)
				self:AddSkillData("Leona",		 	true,	 _Q,	 300,	 "Q (Shield Of Daybreak)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 false)
				self:AddSkillData("Fiora",		 	true,	 _E,	 300,	 "E (Burst Of Speed)",	 	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 true)
				self:AddSkillData("Blitzcrank",		true,	 _E,	 300,	 "E (Power Fist)",	 		SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 true)
				self:AddSkillData("Shyvana",		true,	 _Q,	 300,	 "Q (Twin Blade)",	 		SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 false)
				self:AddSkillData("Renekton",		true,	 _W,	 300,	 "W (Ruthless Predator)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 true)
				self:AddSkillData("Jax",		 	true,	 _W,	 300,	 "W (Empower)",	 			SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 true)
				self:AddSkillData("XinZhao",		true,	 _Q,	 300,	 "Q (Three Talon Strike)",	SPELL_SELF,	 			0,	 true,	 	true,	 				2,	 		0,	 	200,	true, 		 true)
				self:AddSkillData("Nunu",			true,	 _E,	 300,	 "E (Snowball)",	 		SPELL_TARGETED,			0,	 false,	 	false,	 				1.45,		250,	200,	false, 		 false)
				self:AddSkillData("Khazix",			true,	 _Q,	 300,	 "Q (Taste Their Fear)",	SPELL_TARGETED,			0,	 true,	 	true,	 				1.45,		250,	200,	false, 		 false)
				self:AddSkillData("Shen",			true,	 _Q,	 300,	 "Q (Vorpal Blade)",	 	SPELL_TARGETED,			0,	 false,	 	false,	 				1.45,		250,	200,	false, 		 false)
				self:AddSkillData("Gangplank",		true,	 _Q,	 625,	 "Q (Parrrley)",	 		SPELL_TARGETED,			0,	 true,	 	true,	 				1.45,		0,		200,	false, 		 false)
				self:AddSkillData("Garen",			true,	 _Q,	 300,	 "Q (Decisive Strike)",	 	SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Jayce",			true,	 _W,	 300,	 "W (Hyper Charge)",	 	SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Leona",			true,	 _Q,	 300,	 "Q (Shield of Daybreak)",	SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Mordekaiser",	true,	 _Q,	 300,	 "Q (Mace of Spades)",		SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Nasus",			true,	 _Q,	 300,	 "Q (Siphoning Strike)",	SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Nautilus",		true,	 _W,	 300,	 "W (Titan's Wrath)",		SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Nidalee",		true,	 _Q,	 300,	 "Q (Takedown)",		    SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Rengar",			true,	 _Q,	 300,	 "Q (Savagery)",		    SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Rengar",			true,	 _Q,	 300,	 "Q (Empowered Savagery)",	SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Shyvana",		true,	 _Q,	 300,	 "Q (Twin Bite)",			SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Talon",			true,	 _Q,	 300,	 "Q (Noxian Diplomacy)",	SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Volibear",		true,	 _Q,	 300,	 "Q (Rolling Thunder",		SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
				self:AddSkillData("Yorick",			true,	 _Q,	 300,	 "Q (Omen of War",			SPELL_SELF,				0,	 true,	 	false,	 				1.45,		0,		200,	false, 		 true)
			end

			function _Data:IsMinion(Minion)
				return self:GetUnitData()[Minion.charName]
			end

			function _Data:AddResetSpell(name)
				self.ResetSpells[name] = true
			end

			function _Data:AddSpellAttack(name)
				self.SpellAttacks[name] = true
			end

			function _Data:AddNoneAttack(name)
				self.NoneAttacks[name] = true
			end

			function _Data:AddChampionData(Champion, ProjSpeed, _GameplayCollisionRadius, Delay, _Priority)
				self.ChampionData[Champion] = {Name = Champion, ProjectileSpeed = ProjSpeed, GameplayCollisionRadius = _GameplayCollisionRadius, BugDelay = Delay and Delay or 0, Priority = _Priority }
			end

			function _Data:GetChampionRole(name)
				return self.ChampionData[name] and self.ChampionData[name].Priority or nil
			end

			function _Data:AddMinionData(Name, delay, ProjSpeed)
				self.MinionData[Name] = {Delay = delay, ProjectileSpeed = ProjSpeed}
			end

			function _Data:AddJungleMonster(Name, Priority)
				self.JungleData[Name] = Priority
			end

			function _Data:GetJunglePriority(Name)
				return self.JungleData[Name]
			end

			function _Data:AddItemData(Name, ID, RequiresTarget, Range)
				self.ItemData[ID] = _Item(Name, ID, RequiresTarget, Range)
			end

			function _Data:AddWardData(_CharName, _Name, _SpellName, _Type, _Range, _Duration)
				table.insert(self.WardData, {CharName = _CharName, Name = _Name, SpellName = _SpellName, Type = _Type, Range = _Range, Duration = _Duration})
			end

			function _Data:AddSkillData(Name, Enabled, Key, Range, DisplayName, Type, MinMana, AfterAttack, ReqAttackTarget, Speed, Delay, Width, Collision, IsReset)
				if myHero.charName == Name then
					local skill = _Skill(Enabled, Key, Range, DisplayName, Type, MinMana, AfterAttack, ReqAttackTarget, Speed, Delay, Width, Collision, IsReset)
					table.insert(self.Skills, skill)
				end
			end

			function _Data:GetProjectileSpeed(name)
				if VP.projectilespeeds[name] then
					return VP.projectilespeeds[name] / 1000
				else
					return self.ChampionData[name] and self.ChampionData[name].ProjectileSpeed or nil
				end
			end

			function _Data:GetGameplayCollisionRadius(name)
				return self.ChampionData[name] and self.ChampionData[name].GameplayCollisionRadius or 65
			end

			function _Data:IsResetSpell(Spell)
				return self.ResetSpells[Spell.name]
			end

			function _Data:IsAttack(Spell)
				return (self.SpellAttacks[Spell.name] or Helper:StringContains(Spell.name, "attack")) and not self.NoneAttacks[Spell.name]
			end

			function _Data:IsJungleMinion(Object)
				return Object and Object.name and self.JungleData[Object.name] ~= nil
			end

			function _Data:IsCannonMinion(Minion)
				return Minion.charName:find("Cannon")
			end

			function _Data:IsWard(Wardd)
				for _, Ward in pairs(self.WardData) do
					if Ward.Name == Wardd.name then
						return true
					end
				end
				return false
			end

			function _Data:IsWardSpell(Spell)
				for _, Ward in pairs(self.WardData) do
					if Ward.SpellName:lower() == Spell.name:lower() then
						return true
					end
				end
				return false
			end

			function _Data:GenerateHitBoxData()
				for i = 1, heroManager.iCount do
					local hero = heroManager:GetHero(i)
					self.EnemyHitBoxes[hero.charName] = Helper:GetDistance(hero.minBBox, hero.maxBBox)
				end
				GetSave("SidasAutoCarry").EnemyHitBoxes = {TimeSaved = GetGameTimer(), Data = self.EnemyHitBoxes}
			end

			function _Data:LoadHitBoxData()
				local HitBoxes = GetSave("SidasAutoCarry").EnemyHitBoxes
				if HitBoxes then
					self.EnemyHitBoxes = HitBoxes.Data
				else
					self:GenerateHitBoxData()
				end
			end

			function _Data:GetHitBoxLastSavedTime()
				local Time = GetSave("SidasAutoCarry").EnemyHitBoxes
				if Time then
					Time = Time.TimeSaved
				else
					Time = math.huge
				end
				return Time
			end

			function _Data:GetOriginalHitBox(Target)
				return self.EnemyHitBoxes[Target.charName]
			end

			function _Data:EnemyIsImmune(Enemy)
				if self.ImmuneEnemies[Enemy.charName] then
					if Enemy.charName == "Tryndamere" and Enemy.health < MyHero:GetTotalAttackDamageAgainstTarget(Enemy) then
						return true
					elseif Enemy.charName ~= "Tryndamere" then
						return true
					end
				end
			end

			function _Data:GetChampionType(Champ)
				local _Type = self.ChampionData[Cham.charName].Priority

				if _Type == 1 then
					return "ADC"
				elseif _Type == 2 then
					return "AP"
				elseif _Type == 3 then
					return "Support"
				elseif _Type == 4 then
					return "Bruiser"
				elseif _Type == 5 then
					return "Tank"
				end

			end

			--[[
			Custom Champion Support
			]]

			class '_Tristana'

			function _Tristana:__init()
				AddTickCallback(function() self:_OnTick() end)
			end

			function _Tristana:_OnTick()
				local SkillE = Skills:GetSkill(_E)
				local range = MyHero.TrueRange
				if SkillE then
					local target = Crosshair:GetTarget()
					if Orbwalker:CanOrbwalkTarget(target) then
						range = Helper:GetDistance(target)
					end

					SkillE.Range = range
				end
			end

			class '_Vayne'

			function _Vayne:__init()

				self.bushWardPos = nil
				self.tp = TargetPredictionVIP(1000, 2200, 0.25)
				self.SpellData = {}
				self.SpellExpired = true

				AddTickCallback(function() self:_OnTick() end)
				AddProcessSpellCallback(function(Unit, Spell) self:_OnProcessSpell(Unit, Spell) end)

				VayneMenu = scriptConfig("Sida's Auto Carry Reborn: Vayne", "sidasacvayne")
				_G.RebornVayneMenu = VayneMenu
				VayneMenu:addSubMenu("Configuration", "sidasacvaynesub")
				VayneMenu:addSubMenu("Allowed Condemn Targets", "sidasacvayneallowed")

				VayneMenu:addParam("toggleMode", "Toggle Mode (Requires Reload)", SCRIPT_PARAM_ONOFF, false)
				if VayneMenu.toggleMode then
					VayneMenu:addParam("Enabled", "Auto-Condemn", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("N"))
				else
					VayneMenu:addParam("Enabled", "Auto-Condemn", SCRIPT_PARAM_ONKEYDOWN, false, 32)
				end
				VayneMenu:permaShow("Enabled")


				VayneMenu.sidasacvaynesub:addParam("condemnClosers", "Auto-Condemn Gap Closers", SCRIPT_PARAM_ONOFF, true)
				VayneMenu.sidasacvaynesub:addParam("pushDistance", "Max Condemn Distance", SCRIPT_PARAM_SLICE, 300, 0, 450, 0)
				VayneMenu.sidasacvaynesub:addParam("condemnSAC", "Only condemn Reborn target", SCRIPT_PARAM_ONOFF, true)
				VayneMenu.sidasacvaynesub:addParam("useWard", "Auto-Trinket Bush", SCRIPT_PARAM_ONOFF, true)

				for i, Enemy in pairs(GetEnemyHeroes()) do
					VayneMenu.sidasacvayneallowed:addParam("enabled"..Enemy.charName, Enemy.charName, SCRIPT_PARAM_ONOFF, true)
				end
			end

			function _Vayne:DoCondemn(Enemy)
				local EnemyPos = VIP_USER and self.tp:GetPrediction(Enemy) or Enemy
				local PushPos = EnemyPos + (Vector(EnemyPos) - myHero):normalized() * VayneMenu.sidasacvaynesub.pushDistance
				if Enemy.x > 0 and Enemy.z > 0 then
					local checks = math.ceil((VayneMenu.sidasacvaynesub.pushDistance) / 65)
					local checkDistance = (VayneMenu.sidasacvaynesub.pushDistance) / checks
					local InsideTheWall = false
					local checksPos
					for k=1, checks, 1 do
						checksPos = Enemy + (Vector(Enemy) - myHero):normalized() * (checkDistance * k)
						local WallContainsPosition = IsWall(D3DXVECTOR3(checksPos.x, checksPos.y, checksPos.z))
						if WallContainsPosition then
							InsideTheWall = true
							break
						end
					end

					if InsideTheWall then
						CastSpell(_E, Enemy)
						if checksPos and VayneMenu.sidasacvaynesub.useWard then
							local bushPos = self:FindNearestNonWall(checksPos.x, checksPos.y, checksPos.z, 100, 20)
							if bushPos and (IsWallOfGrass(bushPos)) then
								self.bushWardPos = {Pos = bushPos, TimeOut = Helper:GetTime() + 2000, Target = Enemy}
							end
						end
					end
				end
			end

			function _Vayne:_OnTick()
				if self.bushWardPos then
					if Helper:GetTime() < self.bushWardPos.TimeOut then
						if not self.bushWardPos.Target.visible then
							local bushPos = self:FindNearestBushSpot(self.bushWardPos.Pos)
							if Helper:GetDistance(bushPos) <= 545 then
								CastSpell(ITEM_7, bushPos.x, bushPos.z)
							end
						end
					else
						self.bushWardPos = nil
					end
				end

				if VayneMenu.Enabled and myHero:CanUseSpell(_E) == READY then
					if VayneMenu.sidasacvaynesub.condemnClosers then
						if not self.SpellExpired and (Helper:GetTime() - self.SpellData.spellCastedTick) <= (self.SpellData.spellRange/self.SpellData.spellSpeed) * 1000 then
							local spellDirection     = (self.SpellData.spellEndPos - self.SpellData.spellStartPos):normalized()
							local spellStartPosition = self.SpellData.spellStartPos + spellDirection
							local spellEndPosition   = self.SpellData.spellStartPos + spellDirection * self.SpellData.spellRange
							local heroPosition = Point(myHero.x, myHero.z)

							local lineSegment = LineSegment(Point(spellStartPosition.x, spellStartPosition.y), Point(spellEndPosition.x, spellEndPosition.y))

							if lineSegment:distance(heroPosition) <= (not self.SpellData.spellIsAnExpetion and 65 or 200) then
								CastSpell(_E, self.SpellData.spellSource)
							end
						else
							self.SpellExpired = true
							self.SpellData = {}
						end
					end

					if VayneMenu.sidasacvaynesub.condemnSAC then
						local Target = AutoCarry.Crosshair.Attack_Crosshair.target
						if ValidTarget(Target, 725) then
							self:DoCondemn(Target)
						end
					else
						for _, Enemy in pairs(GetEnemyHeroes()) do
							if VayneMenu.sidasacvayneallowed["enabled"..Enemy.charName] and ValidTarget(Enemy, 725) then
								self:DoCondemn(Enemy)
							end
						end
					end
				end
			end

			function _Vayne:FindNearestBushSpot(Pos)
				local lastBush = Pos
				local Distance = Helper:GetDistance(Pos)
				MyPos = Vector(myHero.x, myHero.y, myHero.z)

				for i = Distance, 0, -1 do
					endPos = Vector(Pos.x, Pos.y, Pos.z)
					checkPos = MyPos - (MyPos - endPos):normalized() * i
					if IsWallOfGrass(D3DXVECTOR3(checkPos.x, checkPos.y, checkPos.z)) then
						lastBush = MyPos - (MyPos - endPos):normalized() * (i + 10)
					else
						break
					end
				end
				return lastBush
			end

			-- Credits to vadash
			function _Vayne:FindNearestNonWall( x0, y0, z0, maxRadius, precision )
				if not IsWall(D3DXVECTOR3(x0, y0, z0)) then return nil end
				local radius, gP = 1, precision or 50
				x0, y0, z0, maxRadius = math.round(x0/gP)*gP, math.round(y0/gP)*gP, math.round(z0/gP)*gP, maxRadius and math.floor(maxRadius/gP) or math.huge
				local function toGamePos(x, y) return x0+x*gP, y0, z0+y*gP end
				while radius<=maxRadius do
					for i = 1, 4 do
						local p = D3DXVECTOR3(toGamePos((i==2 and radius) or (i==4 and -radius) or 0,(i==1 and radius) or (i==3 and -radius) or 0))
						if not IsWall(p) then return p end
					end
					local f, x, y = 1-radius, 0, radius
					while x<y-1 do
						x = x + 1
						if f < 0 then f = f+1+x+x
						else y, f = y-1, f+1+x+x-y-y end
						for i=1, 8 do
							local w = math.ceil(i/2)%2==0
							local p = D3DXVECTOR3(toGamePos(((i+1)%2==0 and 1 or -1)*(w and x or y),(i<=4 and 1 or -1)*(w and y or x)))
							if not IsWall(p) then return p end
						end
					end
					radius = radius + 1
				end
			end

			function _Vayne:_OnProcessSpell(Unit, Spell)
				if not VayneMenu.sidasacvaynesub.condemnClosers then
					return
				end

				local GetGapCloser = {
					['Aatrox']      = {true, spell = _Q,                  range = 1000,  projSpeed = 1200, },
					['Akali']       = {true, spell = _R,                  range = 800,   projSpeed = 2200, },
					['Alistar']     = {true, spell = _W,                  range = 650,   projSpeed = 2000, },
					['Diana']       = {true, spell = _R,                  range = 825,   projSpeed = 2000, },
					['Gragas']      = {true, spell = _E,                  range = 600,   projSpeed = 2000, },
					['Graves']      = {true, spell = _E,                  range = 425,   projSpeed = 2000, exeption = true },
					['Hecarim']     = {true, spell = _R,                  range = 1000,  projSpeed = 1200, },
					['Irelia']      = {true, spell = _Q,                  range = 650,   projSpeed = 2200, },
					['JarvanIV']    = {true, spell = jarvanAddition,      range = 770,   projSpeed = 2000, }, -- Skillshot/Targeted ability
					['Jax']         = {true, spell = _Q,                  range = 700,   projSpeed = 2000, },
					['Jayce']       = {true, spell = 'JayceToTheSkies',   range = 600,   projSpeed = 2000, },
					['Khazix']      = {true, spell = _E,                  range = 900,   projSpeed = 2000, },
					['Leblanc']     = {true, spell = _W,                  range = 600,   projSpeed = 2000, },
					['LeeSin']      = {true, spell = 'blindmonkqtwo',     range = 1300,  projSpeed = 1800, },
					['Leona']       = {true, spell = _E,                  range = 900,   projSpeed = 2000, },
					['Malphite']    = {true, spell = _R,                  range = 1000,  projSpeed = 1500 + Unit.ms},
					['Maokai']      = {true, spell = _Q,                  range = 600,   projSpeed = 1200, },
					['MonkeyKing']  = {true, spell = _E,                  range = 650,   projSpeed = 2200, },
					['Pantheon']    = {true, spell = _W,                  range = 600,   projSpeed = 2000, },
					['Poppy']       = {true, spell = _E,                  range = 525,   projSpeed = 2000, },
					['Renekton']    = {true, spell = _E,                  range = 450,   projSpeed = 2000, },
					['Sejuani']     = {true, spell = _Q,                  range = 650,   projSpeed = 2000, },
					['Shen']        = {true, spell = _E,                  range = 575,   projSpeed = 2000, },
					['Tristana']    = {true, spell = _W,                  range = 900,   projSpeed = 2000, },
					['Tryndamere']  = {true, spell = 'Slash',             range = 650,   projSpeed = 1450, },
					['XinZhao']     = {true, spell = _E,                  range = 650,   projSpeed = 2000, },
				}

				if Unit.type == myHero.type and Unit.team ~= myHero.team and GetGapCloser[Unit.charName] and Helper:GetDistance(Unit) < 2000 and Spell ~= nil then
					if Spell.name == (type(GetGapCloser[Unit.charName].spell) == 'number' and Unit:GetSpellData(GetGapCloser[Unit.charName].spell).name or GetGapCloser[Unit.charName].spell) then
						if Spell.target and Spell.target.name == myHero.name or GetGapCloser[Unit.charName].spell == 'blindmonkqtwo' then
							CastSpell(_E, Unit)
						else
							self.SpellExpired = false
							self.SpellData = {
								spellSource = Unit,
								spellCastedTick = Helper:GetTime(),
								spellStartPos = Point(Spell.startPos.x, Spell.startPos.z),
								spellEndPos = Point(Spell.endPos.x, Spell.endPos.z),
								spellRange = GetGapCloser[Unit.charName].range,
								spellSpeed = GetGapCloser[Unit.charName].projSpeed,
								spellIsAnExpetion = GetGapCloser[Unit.charName].exeption or false,
							}
						end
					end
				end
			end

			--[[ Cone Helper by llama ]]

			function areClockwise(testv1,testv2)
				return -testv1.x * testv2.y + testv1.y * testv2.x>0 --true if v1 is clockwise to v2
			end
			function sign(x)
				if x> 0 then return 1
				elseif x<0 then return -1
				end
			end
			function GetCone(radius,theta)
				--Build table of enemies in range
				n = 1
				v1,v2,v3 = 0,0,0
				largeN,largeV1,largeV2 = 0,0,0
				theta1,theta2,smallBisect = 0,0,0
				coneTargetsTable = {}

				for i = 1, heroManager.iCount, 1 do
					hero = heroManager:getHero(i)
					if ValidTarget(hero,radius) then-- and inRadius(hero,radius*radius) then
						coneTargetsTable[n] = hero
						n=n+1
					end
				end

				if #coneTargetsTable>=2 then -- true if calculation is needed
					--Determine if angle between vectors are < given theta
					for i=1, #coneTargetsTable,1 do
						for j=1,#coneTargetsTable, 1 do
							if i~=j then
								--Position vector from player to 2 different targets.
								v1 = Vector(coneTargetsTable[i].x-player.x , coneTargetsTable[i].z-player.z)
								v2 = Vector(coneTargetsTable[j].x-player.x , coneTargetsTable[j].z-player.z)
								thetav1 = sign(v1.y)*90-math.deg(math.atan(v1.x/v1.y))
								thetav2 = sign(v2.y)*90-math.deg(math.atan(v2.x/v2.y))
								thetaBetween = thetav2-thetav1

								if (thetaBetween) <= theta and thetaBetween>0 then --true if targets are close enough together.
									if #coneTargetsTable == 2 then --only 2 targets, the result is found.
										largeV1 = v1
										largeV2 = v2
								else
									--Determine # of vectors between v1 and v2
									tempN = 0
									for k=1, #coneTargetsTable,1 do
										if k~=i and k~=j then
											--Build position vector of third target
											v3 = Vector(coneTargetsTable[k].x-player.x , coneTargetsTable[k].z-player.z)
											--For v3 to be between v1 and v2
											--it must be clockwise to v1
											--and counter-clockwise to v2
											if areClockwise(v3,v1) and not areClockwise(v3,v2) then
												tempN = tempN+1
											end
										end
									end
									if tempN > largeN then
										--store the largest number of contained enemies
										--and the bounding position vectors
										largeN = tempN
										largeV1 = v1
										largeV2 = v2
									end
								end
								end
							end
						end
				end
				elseif #coneTargetsTable==1 then
					return coneTargetsTable[1]
				end

				if largeV1 == 0 or largeV2 == 0 then
					--No targets or one target was found.
					return nil
				else
					--small-Bisect the two vectors that encompass the most vectors.
					if largeV1.y == 0 then
						theta1 = 0
					else
						theta1 = sign(largeV1.y)*90-math.deg(math.atan(largeV1.x/largeV1.y))
					end
					if largeV2.y == 0 then
						theta2 = 0
					else
						theta2 = sign(largeV2.y)*90-math.deg(math.atan(largeV2.x/largeV2.y))
					end

					smallBisect = math.rad((theta1 + theta2) / 2)
					vResult = {}
					vResult.x = radius*math.cos(smallBisect)+player.x
					vResult.y = player.y
					vResult.z = radius*math.sin(smallBisect)+player.z

					return vResult
				end
			end


						--[[ Initialize Classes ]]
				AutoCarry.Skills 		= _Skills()
				AutoCarry.Keys  		= _Keys()
				AutoCarry.Items 		= _Items()
				AutoCarry.Helper 		= _Helper()
				AutoCarry.Data 			= _Data()
				AutoCarry.Jungle 		= _Jungle()
				AutoCarry.MyHero 		= _MyHero()
				_DamagePred()
				AutoCarry.Minions 		= _Minions()
				AutoCarry.Crosshair 	= _Crosshair(DAMAGE_PHYSICAL, MyHero.TrueRange, 0, false, false)
				AutoCarry.Orbwalker 	= _Orbwalker()
				AutoCarry.Plugins 		= _Plugins()
				--AutoCarry.Wards 		= _Wards()
				Skills, Keys, Items, Data, Jungle, Helper, MyHero, Minions, Crosshair, Orbwalker = Helper:GetClasses()
				_MenuManager()
				_Drawing()
				--_ChampionBuffs()
				--_AntiCancel()
				AutoCarry.Structures 	= _Structures()
				_MinionDraw()
				Streaming:CreateMenu()
				local _, files = ScanDirectory(BOL_PATH.."Scripts\\SidasAutoCarryPlugins")
				for _, file in pairs(files) do
					dofile(BOL_PATH.."Scripts\\SidasAutoCarryPlugins\\"..AutoCarry.Helper:TrimString(file))
				end

				if myHero.charName == "Vayne" then
					_Vayne()
				elseif myHero.charName == "Tristana" then
					_Tristana()
				end

				--	PrintSystemMessage("Valid license found. Loaded as "..(VIP_USER and "VIP" or "Non-VIP").." user.")


				--[[
				Legacy Plugin Support
				Plugins should be updated, this may be removed after a few months.
				]]

				--AutoCarry.Orbwalker = AutoCarry.Crosshair.Attack_Crosshair
				AutoCarry.SkillsCrosshair = AutoCarry.Crosshair.Skills_Crosshair
				AutoCarry.CanMove = true
				AutoCarry.CanAttack = true
				AutoCarry.MainMenu = {}
				AutoCarry.PluginMenu = nil
				AutoCarry.EnemyTable = GetEnemyHeroes()
				AutoCarry.shotFired = false
				AutoCarry.OverrideCustomChampionSupport = false
				AutoCarry.CurrentlyShooting = false
				DoneInit = true


				class '_LegacyPlugin'

				function _LegacyPlugin:__init()
					AutoCarry.PluginMenu = scriptConfig("Sida's Auto Carry Plugin: "..myHero.charName, "sidasacplugin"..myHero.charName)
					require("SidasAutoCarryPlugin - "..myHero.charName)
					PrintSystemMessage("Loaded "..myHero.charName.." plugin!")
					AddTickCallback(function() self:_OnTick() end)

					if PluginOnTick then
						AddTickCallback(function() PluginOnTick() end)
					end
					if PluginOnDraw then
						AddDrawCallback(function() PluginOnDraw() end)
					end
					if PluginOnCreateObj then
						AddCreateObjCallback(function(obj) PluginOnCreateObj(obj) end)
					end
					if PluginOnDeleteObj then
						AddDeleteObjCallback(function(obj) PluginOnDeleteObj(obj) end)
					end
					if PluginOnLoad then
						PluginOnLoad()
					end
					if PluginOnUnload then
						AddUnloadCallback(function() PluginOnUnload() end)
					end
					if PluginOnWndMsg then
						AddMsgCallback(function(msg, key) PluginOnWndMsg(msg, key) end)
					end
					if PluginOnProcessSpell then
						AddProcessSpellCallback(function(unit, spell) PluginOnProcessSpell(unit, spell) end)
					end
					if PluginOnSendChat then
						AddChatCallback(function(text) PluginOnSendChat(text) end)
					end
					if PluginOnBugsplat then
						AddBugsplatCallback(function() PluginOnBugsplat() end)
					end
					if PluginOnAnimation then
						AddAnimationCallback(function(unit, anim) PluginOnAnimation(unit, anim) end)
					end
					if PluginOnSendPacket then
						AddSendPacketCallback(function(packet) PluginOnSendPacket(packet) end)
					end
					if PluginOnRecvPacket then
						AddRecvPacketCallback(function(packet) PluginOnRecvPacket(packet) end)
					end
					if PluginOnApplyParticle then
						AddParticleCallback(function(unit, particle) PluginOnApplyParticle(unit, particle) end)
					end
					if OnAttacked then
						RegisterOnAttacked(OnAttacked)
					end
					if PluginBonusLastHitDamage then
						Plugins:RegisterBonusLastHitDamage(PluginBonusLastHitDamage)
					end

					if CustomAttackEnemy then
						Plugins:RegisterPreAttack(CustomAttackEnemy)
					end
				end

				function _LegacyPlugin:_OnTick()
					AutoCarry.MainMenu.AutoCarry = AutoCarryMenu.Active
					AutoCarry.MainMenu.LastHit = LastHitMenu.Active
					AutoCarry.MainMenu.MixedMode = MixedModeMenu.Active
					AutoCarry.MainMenu.LaneClear = LaneClearMenu.Active
					MyHero:MovementEnabled(AutoCarry.CanMove)
					MyHero:AttacksEnabled(AutoCarry.CanAttack)
					if #AutoCarry.EnemyTable < #Helper.EnemyTable then
						AutoCarry.EnemyTable = Helper.EnemyTable
					end
				end

				AutoCarry.GetAttackTarget = function(isCaster)
					return Crosshair:GetTarget()
				end

				AutoCarry.GetKillableMinion = function()
					return Minions.KillableMinion
				end

				AutoCarry.GetMinionTarget = function()
					return nil
				end

				AutoCarry.EnemyMinions = function()
					return Minions.EnemyMinions
				end

				AutoCarry.AllyMinions = function()
					return Minions.AllyMinions
				end

				AutoCarry.GetJungleMobs = function()
					return Jungle.JungleMonsters
				end

				AutoCarry.GetLastAttacked = function()
					return Orbwalker.LastEnemyAttacked
				end

				AutoCarry.GetNextAttackTime = function()
					return Orbwalker:GetNextAttackTime()
				end

				AutoCarry.CastSkillshot = function (skill, target)
					if VIP_USER then
						pred = TargetPredictionVIP(skill.range, skill.speed*1000, (skill.delay/1000 - (GetLatency()/2)/1000), skill.width)
					elseif not VIP_USER then
						pred = TargetPrediction(skill.range, skill.speed, skill.delay, skill.width)
					end
					local predPos = pred:GetPrediction(target)
					if predPos and Helper:GetDistance(predPos) <= skill.range then
						if VIP_USER then --TODO
							if not skill.minions or not AutoCarry.GetCollision(skill, myHero, predPos) then
								CastSpell(skill.spellKey, predPos.x, predPos.z)
						end
						elseif not VIP_USER then
							if not skill.minions or not AutoCarry.GetCollision(skill, myHero, predPos) then
								CastSpell(skill.spellKey, predPos.x, predPos.z)
							end
						end
					end
				end

				AutoCarry.GetCollision = function (skill, source, destination)
					if VIP_USER then
						local col = Collision(skill.range, skill.speed*1000 , (skill.delay/1000 - (GetLatency()/2)/1000), skill.width)
						return col:GetMinionCollision(source, destination)
					else
						return willHitMinion(destination, skill.width)
					end
				end

				AutoCarry.GetPrediction = function(skill, target)
					if VIP_USER then
						pred = TargetPredictionVIP(skill.range, skill.speed*1000, skill.delay/1000, skill.width)
					elseif not VIP_USER then
						pred = TargetPrediction(skill.range, skill.speed, skill.delay, skill.width)
					end
					return pred:GetPrediction(target)
				end

				AutoCarry.IsValidHitChance = function(skill, target)
					return true
				end

				AutoCarry.GetProdiction = function(Key, Range, Speed, Delay, Width, Source, Callback)
					return AutoCarry.Plugins:GetProdiction(Key, Range, Speed, Delay, Width, Source, Callback)
				end

				function willHitMinion(predic, width)
					for _, minion in pairs(Minions.EnemyMinions.objects) do
						if minion ~= nil and minion.valid and string.find(minion.name,"Minion_") == 1 and minion.team ~= player.team and minion.dead == false then
							if predic ~= nil then
								ex = player.x
								ez = player.z
								tx = predic.x
								tz = predic.z
								dx = ex - tx
								dz = ez - tz
								if dx ~= 0 then
									m = dz/dx
									c = ez - m*ex
								end
								mx = minion.x
								mz = minion.z
								distanc = (math.abs(mz - m*mx - c))/(math.sqrt(m*m+1))
								if distanc < width and math.sqrt((tx - ex)*(tx - ex) + (tz - ez)*(tz - ez)) > math.sqrt((tx - mx)*(tx - mx) + (tz - mz)*(tz - mz)) then
									return true
								end
							end
						end
					end
					return false
				end

				if FileExist(LIB_PATH .."SidasAutoCarryPlugin - "..myHero.charName..".lua") then
					_LegacyPlugin()
				end



			AddTickCallback(function()
				if Keys.LastHit then
					Minions:LastHit()
				end

				if Keys.AutoCarry then
					Items:UseAll(Crosshair.Attack_Crosshair.target)
					Skills:CastAll(Crosshair:GetTarget())
					if Orbwalker:CanOrbwalkTarget(Crosshair.Attack_Crosshair.target) then
						Orbwalker:Orbwalk(Crosshair.Attack_Crosshair.target)
					elseif Structures:CanOrbwalkStructure() then
						Orbwalker:OrbwalkIgnoreChecks(Structures:GetTargetStructure())
					else
						Orbwalker:Orbwalk(Jungle:GetFocusedMonster())
					end
				end

				if Keys.LaneClear then
					Skills:CastAll(Crosshair:GetTarget())
					Minions:LaneClear()
				end

				if Keys.MixedMode then
					Skills:CastAll(Crosshair:GetTarget())
					if MixedModeMenu.MinionPriority and Orbwalker:CanOrbwalkTarget(Minions.KillableMinion) and not ConfigurationMenu.SupportMode then
						Orbwalker:Orbwalk(Minions.KillableMinion)
					elseif Orbwalker:CanOrbwalkTarget(Crosshair.Attack_Crosshair.target) then
						Items:UseAll(Crosshair.Attack_Crosshair.target)
						Orbwalker:Orbwalk(Crosshair.Attack_Crosshair.target)
					else
						Minions:LastHit()
					end
				end

				if not Keys.AutoCarry and not Keys.MixedMode and not Keys.LaneClear and not Keys.LastHit and MinionHealthMenu.MinionMarker then
					Minions:MarkerOnly()
				end
			end)

			end

			DelayAction(function() _NewUpdate() end, 2)

    else
      reason = dePack[r({114,101,97,115,111,110})]
      PrintSystemMessage("Invalid User.")
      --PrintChat(r({62,62,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,45,32})..reason..r({32,60,60}))
    end
  end
  if not dePack[r({115,116,97,116,117,115})] then
  	PrintSystemMessage("Failed to login.")
    PrintChat(r({62,62,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,60,60}))
  end
end

