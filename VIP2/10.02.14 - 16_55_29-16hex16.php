<?php exit() ?>--by 16hex16 105.236.253.215
--[[
$Name: AIOCombo
$Version: 0.001
$Author: Pain
$Credits: HeX
$Information: N/A at the moment

$Current Supported Champions:
	Blitzcrank - Q,W,E,R
	Thresh - Q,W,E,R
--]]

--[[
Encrypt All Below.
--]]

versionGOE = 0.001

if not VIP_USER then print("AIOCombo: Denied. VIP Only") return end

PressLoad = false
if debug.getinfo(GetUser).linedefined > -1 then print("AIOCombo: Denied") return end
UserContent = {"xpain",
"klokje",
"empty1991",
"hex",
"burn",
"pqmailer",
"kevinkev",
"astrostar",
"apple",
"ragehunter3",
"trees",
"weee",
"eddow",
"3rasus",
"tacd",
"biggin3",
"wukeokok",
"hans_meier",
"solenrus",
"jta87k",
"seamlessly",
"sunnr",
"herpaderpa123",
"staf",
"mewkyy",
"widelove",
"gianmaranon",
"web38",
"quixor329",
"damnation",
"kihan112",
"kobe2324",
"ddsq1226",
"gee4hire",
"vinah",
"phexon",
"ballsdeep",
"rosenkrantz",
"crackle",
"dansa",
"johnnywalker",
"darkdusk",
"verajicus",
"araaj",
"ragekid",
"andythesk8r",
"ljk3322",
"khicon",
"xtony211",
"banned4haxx",
"xeph",
"jae",
"kev",
"dragonne",
"63777377",
"tali0206",
"phebos",
"ejdernefesi",
"jiimmyp",
"phbn93",
"maxemz",
"kingkidd",
"skito",
"kpone53",
"ijuno",
"johay",
"prasinos",
"theapemancometh",
"mrj",
"diwas89",
"darkood",
"ghostrider9310",
"kaotik",
"gintoci",
"vice2230"}

local authed = false
local UserName = string.lower(GetUser())
for _, users in pairs(UserContent) do
	if UserName == string.lower(users) then
		authed = true
		FakeLoad = true
		PissOff = false
		PrintChat("AIOCombo: Accepted")
		PressLoad = true
		break
	end
end
if not authed then PissOff = true print("AIOCombo: Denied") return end
VP = nil
EmergencyStop = false

--[[
AA KEY

1 = AARange (Primary Range)
2 = windUpTime (Primary wind up time)
3 = animationTime (Primary anim time)

4 = AARange (Secondary Range/BuffedRange)
5 = windUpTime (Secondary wind up time)
6 = animationTime (Secondary anim time)

7 = BuffedSpell (Buffering Spell)
8 = BuffedDuration (Buff Duration)


SPELL KEY

Primary:
1 = spellRange (Range)
2 = spellSpeed (Speed)
3 = spellDelay (Delay)
4 = spellWidth (Width)
5 = spellCol (Collision)
6 = spellType (Spell Type: Line, Circular, Still, Self, Heal, Target)
7 = spellHC (Spell HitChance)
8 = spellBuffName (Buff Name)
9 = spellObjName (Object Name)

Secondary:
10 = spellRange (Range)
11 = spellSpeed (Speed)
12 = spellDelay (Delay)
13 = spellWidth (Width)
14 = spellCol (Collision)
15 = spellType (Spell Type: Line, Circular, Still, Self, Heal, Target)
16 = spellHC (Spell HitChance)
17 = spellBuffName (Buff Name)
18 = spellObjName (Object Name)
--]]
local ComboTable = {
	Aatrox = {
		DT = {DAMAGE_PHYSICAL},
		AA = {125,1,1,300,1,1,_R,12},
		q = {800,1800,0.27,100,false,"Circular",2},
		w = {350,0,0,0,false,"Self",0},
		e = {1000,1600,0.27,40,false,"Line",3},
		r = {550,0,0.5,false,"Still",2}, 
		Harass = {0,0,1,0},
		Combo = {1,1,1,1},
	},
	Ahri = {
		DT = {DAMAGE_MAGICAL},
		AA = {550,1,1}, 
		q = {870,1800,0.250,90,false,"Line",2},
		w = {800,0,0,0,false,"Self",0},
		e = {975, 1600,0.250,80,true,"Line",2},
		r = {600,0,0.2,50,false,"Line",1}, 
		Harass = {1,0,1,0},
		Combo = {1,1,1,1},
	},
	Akali = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1},
		q = {600,0,0,0,false,"Target",1},
		w = {700,0,0.5,150,false,"Self",0},
		e = {325,0,0.3,0,false,"Still",1},
		r = {800,0,0,0,false,"Target",1}, 
		Harass = {1,0,1,0},
		Combo = {1,0,1,1},
	},
	Alistar = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1}, 
		q = {365,0,0.3,0,false,"Still",2},
		w = {650,0,0,0,false,"Target",1},
		e = {575,0,0,0,false,"Heal",1},
		r = {120,0,0,0,false,"Self",0}, 
		Harass = {1,0,0,0},
		Combo = {1,1,0,1},
		Protect = {0,0,1,0},
	},
	Amumu = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1},
		q = {1100,2050,0.25,95,true,"Line",2},
		w = {300,0,0.25,0,false,"Still",1,nil,"Despairpool_tar.troy"},
		e = {350,0,0.3,0,false,"Still",1},
		r = {550,0,0.3,0,false,"Still",2}, 
		Harass = {0,0,0,0},
		Combo = {1,1,1,1},
		
	},
	Anivia = {
		DT = {DAMAGE_MAGICAL},
		AA = {600,1,1}, 
		q = {1100,860,0.25,110,false,"Line",2},
		w = {1000,0,0.5,50,false,"Circular",2},
		e = {650,0,0,0,false,"Target",1},
		r = {625,0,0,200,false,"Circular",2,nil,"cryo_storm_green_team.troy"}, 
		Harass = {1,0,1,0},
		Combo = {1,1,1,1},
	},
	Annie = {
		DT = {DAMAGE_MAGICAL},
		AA = {625,1,1}, 
		q = {625,0,0,0,0,false,"Target",1},
		w = {625,0,0.5,30,false,"Circular",2},
		e = {600,0,0,0,false,"Self",0},
		r = {600,0,0.5,100,false,"Circular",2}, 
		Harass = {1,1,0,0},
		Combo = {1,1,1,1},
	},
	Ashe = {
		DT = {DAMAGE_PHYSICAL},
		AA = {600,1,1}, 
		q = {0,0,0,0,false,"Self",0},
		w = {600,900,0.5,80,true,"Line",2},
		e = {0,0,0,0,false,nil,1},
		r = {math.huge,1600,0.5,130,false,"Line",3}, 
		Harass = {0,1,0,0},
		Combo = {1,1,1,1},
	},
	Blitzcrank = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1},
		q = {925,1800,0.22,75,true,"Line",2},
		w = {600,0,0,0,false,"Self",0},
		e = {150,0,0,0,false,"Self",0},
		r = {520,0,0.5,50,false,"Still",2}, 
		Harass = {1,0,1,0},
		Combo = {1,1,1,1},
	},
	Caitlyn = {
		DT = {DAMAGE_PHYSICAL},
		AA = {650,1,1}, 
		q = {1250,2200,0.25,90,false,"Line",2},
		w = {800,1450,0.5,80,false,"Line",2},
		e = {950,2000,0.7,80,true,"Line",2},
		r = {1500 + (500 * myHero:GetSpellData(_R).level),0,0,0,false,"Target",1}, 
		Harass = {1,0,0,0},
		Combo = {1,1,1,1},
	},
	Cassiopeia = {
		DT = {DAMAGE_MAGICAL},
		AA = {550,1,1}, 
		q = {850,20,0.5,80,false,"Circular",2},
		w = {850,0,0.5,100,false,"Circular",2},
		e = {700,0,0,0,false,"Target",1},
		r = {815,0,0.75,100,false,"Circular",2}, 
		Harass = {1,0,1,0},
		Combo = {1,1,1,1},
	},
	Chogath = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1}, 
		q = {950,20,0.9,100,false,"Circular",2},
		w = {300,0,0.25,80,false,"Circular",2},
		e = {0,0,0,0,false,"CastOnce",1},
		r = {150,0,0,0,false,"Target",1}, 
		Harass = {1,1,0,0},
		Combo = {1,1,0,1},
	},
	Corki = {
		DT = {DAMAGE_PHYSICAL},
		AA = {550,1,1}, 
		q = {825,0,0.5,100,false,"Circular",2},
		w = {800,700,0,100,false,"Line",2},
		e = {600,0,0,0,false,"Self",2},
		r = {1225,825.5,0.5,60,true,"Line",2}, 
		Harass = {1,0,0,1},
		Combo = {1,1,1,1},
	},
	Darius = {
		DT = {DAMAGE_PHYSICAL},
		AA = {125,1,1}, 
		q = {270,0,0.5,0,false,"Still",2},
		w = {125,0,0,0,false,"Self",0},
		e = {550,1500,0.5,80,false,"Circular",2},
		r = {475,20,0.5,0,false,"Target",1}, 
		Harass = {1,0,0,0},
		Combo = {1,1,1,1},
	},
	Diana = {
		DT = {DAMAGE_MAGICAL},
		AA = {150,1,1}, 
		q = {900,20,0.5,100,false,"Circular",2},
		w = {400,0,0.5,0,false,"Still",2},
		e = {450,0,0.5,0,false,"Still",2},
		r = {825,0,0,0,false,"Target",2}, 
		Harass = {1,0,0,0},
		Combo = {1,1,1,1},
	},
	DrMundo = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1},
		q = {900,1500,0.5,75,true,"Line",2},
		w = {325,0,0,0,false,"Still",1},
		e = {0,0,0,0,false,"Self",0},
		r = {0,0,0,0,false,"Self",0}, 
		Harass = {1,0,0,0},
		Combo = {1,1,1,1},
	},
	Draven = {
		DT = {DAMAGE_PHYSICAL},
		AA = {550,1,1}, 
		q = {550,0,0,0,false,"Self",0},
		w = {550,0,0,0,false,"Self",0},
		e = {1050,1600,0.5,130,false,"Line",2},
		r = {math.huge,2000,1,160,false,"Line",3}, 
		Harass = {0,0,1,0},
		Combo = {1,1,1,1},
	},
	Elise = {
		DT = {DAMAGE_MAGICAL},
		AA = {550,1,1,125,1,1}, 
		q = {625,0,0,0,false,"Target",1,nil,nil,475,0,0,0,false,"Target",1},
		w = {950,1.2,0.35,100,true,"Line",1,nil,nil,225,0,0,0,false,"Self",0},
		e = {1075,1.4,0.25,70,true,"Line",2,nil,nil,975,20,0,0,false,"Target",1},
		r = {0,0,0,0,false,"Self",0,nil,nil,0,0,0,0,false,"Self",0}, 
		Harass = {1,1,1,0,1,0,0,0},
		Combo = {1,1,1,1,1,1,1,1},
	},
	Evelynn = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1}, 
		q = {500,20,0.5,80,false,"Self",2},
		w = {500,0,0,0,false,"Self",0},
		e = {225,0,0,0,false,"Target",1},
		r = {650,0,0.6,100,false,"Circular",2}, 
		Harass = {1,0,1,0},
		Combo = {1,1,1,1},
	},
	Ezreal = {
		DT = {DAMAGE_PHYSICAL},
		AA = {550,1,1}, 
		q = {1150,1200,0.5,80,true,"Line",2},
		w = {1000,1200,0.5,80,false,"Line",2},
		e = {475,0,0.5,80,false,"Circular",1},
		r = {math.huge,2000,1,160,false,"Line",3}, 
		Harass = {1,1,0,0},
		Combo = {1,1,1,1},
	},
	FiddleSticks = {
		DT = {DAMAGE_MAGICAL},
		AA = {480,1,1}, 
		q = {575,0,0,0,false,"Target",1},
		w = {475,0,0,0,false,"Target",1,nil,"Drain.troy"},
		e = {750,0,0,0,false,"Target",1}, 
		r = {800,0,1.7,400,false,"Circular",2},
		Harass = {1,0,1,0},
		Combo = {1,1,1,1},
	},
	Fiora = {
		DT = {DAMAGE_PHYSICAL},
		AA = {125,1,1},
		q = {600,0,0,0,false,"Target",1},
		w = {125,0,0,0,false,"Self",0},
		e = {125,0,0,0,false,"Self",0},
		r = {400,0,0,0,false,"Target",1},
		Harass = {1,0,0,0},
		Combo = {1,1,1,1},
	},
	Fizz = {
		DT = {DAMAGE_MAGICAL},
		AA = {175,1,1},
		q = {550,0,0,0,false,"Target",1},
		w = {200,0,0,0,false,"Target",1},
		e = {600,0,0.5,80,false,"Circular",2},
		r = {1000,1200,0.5,100,false,"Line",2}, 
		Harass = {1,1,0,0},
		Combo = {1,1,1,1},
	},
	Galio = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1}, 
		q = {900,1300,0.5,120,false,"Line",2},
		w = {800,0,0,0,false,"Shield",0},
		e = {1180,1200,0.5,120,false,"Line",2},
		r = {550,0,0.5,0,false,"Still",3}, 
		Harass = {1,0,1,0},
		Combo = {1,0,1,1},
		Protect = {0,1,0,0},
	},
	Gangplank = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, 
		q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Garen = {
		DT = {DAMAGE_PHYSICAL},
		AA = {},
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Gragas = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Graves = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, 
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Hecarim = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, 
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Heimerdinger = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Irelia = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, 
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Janna = {
		DT = {DAMAGE_MAGICAL},
		AA = {475,1,1}, 
		q = {1300,800,0,200,false,"Line",2},
		w = {600,0,0,0,false,"Target",1},
		e = {0,800,0,0,false,"Shield",0},
		r = {725,0,0,0,false,"Still",1}, 
		Harass = {1,1,0,0},
		Combo = {1,1,0,0},
		Protect = {0,0,1,1},
	},
	Jarvan = {
		DT = {DAMAGE_PHYSICAL},
		AA = {},
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Jax = {
		DT = {DAMAGE_PHYSICAL},
		AA = {},
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Jayce = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, 
		q = {},
		w = {},
		e = {},
		r = {},
		Harass = {},
	},
	Jinx = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, 
		q = {},
		w = {},
		e = {},
		r = {},
		Harass = {},
	},
	Karma = {
		DT = {DAMAGE_MAGICAL},
		AA = {525,1,1}, 
		q = {950,1650,0.265,100,true,"Line",2},
		w = {675,0,0,0,false,"Target",1},
		e = {800,0,0,0,false,"Shield",0},
		r = {960,0,0,0,false,"Self",0}, 
		Harass = {1,1,0,0},
		Combo = {1,1,0,1},
		Protect = {0,0,1,0},
	},
	Karthus = {
		DT = {DAMAGE_MAGICAL},
		AA = {},
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Kassadin = {
		DT = {DAMAGE_MAGICAL},
		AA = {},
		q = {},
		w = {},
		e = {},
		r = {},
		Harass = {},
	},
	Katarina = {
		DT = {DAMAGE_MAGICAL},
		AA = {},
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Kayle = {
		DT = {DAMAGE_MAGICAL},
		AA = {},
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Kennen = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, 
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Khazix = {
		DT = {DAMAGE_PHYSICAL},
		AA = {},
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	Kogmaw = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, 
		q = {},
		w = {},
		e = {},
		r = {}, 
		Harass = {},
	},
	LeBlanc = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Leesin = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Leona = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1},
		q = {150,0,0,0,false,"Self",0},
		w = {450,nil,nil,nil,"Self",0},
		e = {900,2000,0,90,"Line",2},
		r = {1200,0,0.3,120,"Circular",3}, 
		Harass = {1,0,1,0},
		Combo = {1,1,1,1},
	},
	Lissandra = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Lucian = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Lulu = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, 
		q = {950,1400,0.25,60,false,"Line",2},
		w = {650,0,0,0,false,"Target",1},
		e = {650,0,0,0,false,"Shield",0},
		r = {900,0,0,0,false,"Shield",0}, 
		Harass = {1,1,0,0},
		Combo = {1,1,0,0},
		Protect = {0,0,1,1},
	},
	Lux = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Malphite = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Malzahar = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Maokai = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	MasterYi = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Missfortune = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Mordekaiser = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Morgana = {
		DT = {DAMAGE_MAGICAL},
		AA = {600,1,1}, 
		q = {1300,12000.225,70,true,"Line",2},
		w = {900,0,0.25,100,false,"Circular",3},
		e = {750,0,0,0,false,"Shield",0},
		r = {625,0,0,0,0.5,false,"Still",3}, 
		Harass = {1,1,0,0},
		Combo = {1,1,0,1},
		Protect = {0,0,1,0},
	},
	Nami = {
		DT = {DAMAGE_MAGICAL},
		AA = {550,1.1,1.1}, 
		q = {875,0,1.075,100,false,"Circular",2},
		w = {725,0,0,0,false,"Heal",0},
		e = {800,0,0,0,false,"Buff",1},
		r = {2700,850,0.5,150,"Line",3}, 
		Harass = {1,0,0,0},
		Combo = {1,1,0,1},
		Protect = {0,1,1,0},
	},
	Nasus = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Nautilus = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Nidalee = {
		DT = {DAMAGE_MAGICAL},
		AA = {525,1,1,125,1,1}, 
		q = {1450,1300,0.25,75,true,"Line",2,nil,nil,150,0,0,0,false,"Self",0},
		w = {900,0,0.15,90,false,"Circular",1,nil,nil,375,0,0.5,50,false,"Circular",1},
		e = {600,0,0,0,false,"Heal",0,nil,nil,400,0,0.5,100,false,"Still",2},
		r = {0,0,0,0,false,"Self",0,nil,nil,0,0,0,0,false,"Self",0}, 
		Harass = {1,1,0,0,1,1,1,0},
		Combo = {1,1,0,1,1,1,1,1},
		Protect = {0,0,1,0,0,0,0,0}
	},
	Nocturne = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Nunu = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Olaf = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Orianna = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Pantheon = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Poppy = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Quinn = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Rammus = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Renekton = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Rengar = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Riven = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Rumble = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Ryze = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Sejuani = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Shaco = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Shen = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
		Harass = {},
	},
	Shyvana = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Singed = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Sion = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Sivir = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Skarner = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Sona = {
		DT = {DAMAGE_MAGICAL},
		AA = {800,1,1}, 
		q = {650,0,0,0,false,"Self",0},
		w = {650,0,0,0,false,"Buff",0},
		e = {1000,0,0,0,false,"Heal",0},
		r = {}, 
		Harass = {1,0,0,0},
		Combo = {1,0,0,0},
		Protect = {0,0,1,0},
	},
	Soraka = {
		DT = {DAMAGE_MAGICAL},
		AA = {550,1,1}, 
		q = {675,0,0,0,false,"Self",0},
		w = {750,0,0,0,false,"Heal",0},
		e = {725,0,0,0,false,"Target",0},
		r = {math.huge,0,0,0,false,"Heal",0}, 
		Harass = {true,nil,true,nil},
		Combo = {true,nil,true,nil},
		Protect = {0,1,1,1},
	},
	Swain = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Syndra = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Talon = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Taric = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Teemo = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Thresh = {
		DT = {DAMAGE_PHYSICAL},
		AA = {550,1,1},
		q = {1100,2000,0.5,95,true,"Line",2},
		w = {950,0,0,0,false,"Shield",1},
		e = {415,0,1.1,80,false,"Circular",1},
		r = {420,0,0.75,50,false,"Still",1}, 
		Harass = {1,0,1,0},
		Combo = {1,0,1,1},
		Protect = {0,1,0,0},
	},
	Tristana = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Trundle = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Tryndamere = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	TwistedFate = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Twitch = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Udyr = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Urgot = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Varus = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Vayne = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Veigar = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Vi = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Viktor = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, 
		q = {600,0,0,0,false,"Target",1},
		w = {625,0,0.75,100,false,"Circular",2},
		e = {540,1200,0.25,50,false,"Line",2},
		r = {700,0,0.250,100,false,"Circular",2},
	},
	Vladimir = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Volibear = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Warwick = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	MonkeyKing = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Xerath = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	XinZhao = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Yasuo = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Zac = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Zed = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Ziggs = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Zilean = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {}, Harass = {},
	},
	Zyra = {
		DT = {DAMAGE_MAGICAL},
		AA = {575,1,1}, 
		q = {800,1400,0.25,100,"Line",2,nil,"zyra_Q_cas.troy"},
		w = {800,1400,0.25,80,"Line",2},
		e = {1100,1000,0.25,300,false,"Line",2},
		r = {1850,0,0.7,200,"Circular",3}, 
		Harass = {1,1,1,0},
		Combo = {1,1,1,1},
	},
}
local cd = ComboTable[myHero.charName]
if cd == nil then PrintChat("AIOCombo: Denied") return end
function RefreshTable()
	if EmergencyStop == true then return end
	FormCheck()
	for i, combat in pairs(ComboTable) do
		if combat == cd then
			ComboVar = myHero.charName
			if cd.AA[1] == nil then print("AIOCombo: Something isn't right. Please contact Pain with your current Champion's Name and what your were doing when you got this message") EmergencyStop = true end
	
			if cd.Combo[1] == 1 then qCombo = true end
			if cd.Combo[2] == 1 then wCombo = true end
			if cd.Combo[3] == 1 then eCombo = true end
			if cd.Combo[4] == 1 then rCombo = true end
			if cd.Combo[5] == 1 then qCombo2 = true end
			if cd.Combo[6] == 1 then wCombo2 = true end
			if cd.Combo[7] == 1 then eCombo2 = true end
			if cd.Combo[8] == 1 then rCombo2 = true end
			
			if cd.Harass[1] == 1 then qHarass = true end
			if cd.Harass[2] == 1 then wHarass = true end
			if cd.Harass[3] == 1 then eHarass = true end
			if cd.Harass[4] == 1 then rHarass = true end
			if cd.Harass[5] == 1 then qHarass2 = true end
			if cd.Harass[6] == 1 then wHarass2 = true end
			if cd.Harass[7] == 1 then eHarass2 = true end
			if cd.Harass[8] == 1 then rHarass2 = true end
			
			if cd.Protect ~= nil then Protect = true
				if cd.Protect[1] == 1 then qProtect = true end
				if cd.Protect[2] == 1 then wProtect = true end
				if cd.Protect[3] == 1 then eProtect = true end
				if cd.Protect[4] == 1 then rProtect = true end
				
				if cd.Protect[5] == 1 then qProtect2 = true end
				if cd.Protect[6] == 1 then wProtect2 = true end
				if cd.Protect[7] == 1 then eProtect2 = true end
				if cd.Protect[8] == 1 then rProtect2 = true end
			end
			
			if PrimaryForm then
				if cd.AA[1] ~= nil then AARange = cd.AA[1] end
				if cd.AA[2] ~= nil then
					windUpTime = (cd.AA[2] * 1000)
					animationTime = (cd.AA[3] * 1000)
				end
				--q Handler
				if cd.q[1] ~= nil then qRange = cd.q[1] end
				if cd.q[2] ~= nil then qSpeed = cd.q[2] end
				if cd.q[3] ~= nil then qDelay = cd.q[3] end
				if cd.q[4] ~= nil then qWidth = cd.q[4] end
				if cd.q[5] ~= nil then qCol = cd.q[5] end
				if cd.q[6] ~= nil then qType = cd.q[6] end
				if cd.q[7] ~= nil then qHC = cd.q[7] end
				if cd.q[8] ~= nil then qBuffName = cd.q[8] end
				if cd.q[9] ~= nil then qObjName = cd.q[9] end
				--w Handler
				if cd.w[1] ~= nil then wRange = cd.w[1] end
				if cd.w[2] ~= nil then wSpeed = cd.w[2] end
				if cd.w[3] ~= nil then wDelay = cd.w[3] end
				if cd.w[4] ~= nil then wWidth = cd.w[4] end
				if cd.w[5] ~= nil then wCol = cd.w[5] end
				if cd.w[6] ~= nil then wType = cd.w[6] end
				if cd.w[7] ~= nil then wHC = cd.w[7] end
				if cd.w[8] ~= nil then wBuffName = cd.w[8] end
				if cd.w[9] ~= nil then wObjName = cd.w[9] end
				--e Handler
				if cd.e[1] ~= nil then eRange = cd.e[1] end
				if cd.e[2] ~= nil then eSpeed = cd.e[2] end
				if cd.e[3] ~= nil then eDelay = cd.e[3] end
				if cd.e[4] ~= nil then eWidth = cd.e[4] end
				if cd.e[5] ~= nil then eCol = cd.e[5] end
				if cd.e[6] ~= nil then eType = cd.e[6] end
				if cd.e[7] ~= nil then eHC = cd.e[7] end
				if cd.e[8] ~= nil then eBuffName = cd.e[8] end
				if cd.e[9] ~= nil then eObjName = cd.e[9] end
				--r Handler
				if cd.r[1] ~= nil then rRange = cd.r[1] end
				if cd.r[2] ~= nil then rSpeed = cd.r[2] end
				if cd.r[3] ~= nil then rDelay = cd.r[3] end
				if cd.r[4] ~= nil then rWidth = cd.r[4] end
				if cd.r[5] ~= nil then rCol = cd.r[5] end
				if cd.r[6] ~= nil then rType = cd.r[6] end
				if cd.r[7] ~= nil then rHC = cd.r[7] end
				if cd.r[8] ~= nil then rBuffName = cd.r[8] end
				if cd.r[9] ~= nil then rObjName = cd.r[9] end
				
			else
				if cd.AA[4] ~= nil then AARange = cd.AA[4] end
				if cd.AA[5] ~= nil then
					windUpTime = (cd.AA[5] * 1000)
					animationTime = (cd.AA[6] * 1000)
				end
				--q Handler
				if cd.q[10] ~= nil then qRange = cd.q[10] end
				if cd.q[11] ~= nil then qSpeed = cd.q[11] end
				if cd.q[12] ~= nil then qDelay = cd.q[12] end
				if cd.q[13] ~= nil then qWidth = cd.q[13] end
				if cd.q[14] ~= nil then qCol = cd.q[14] end
				if cd.q[15] ~= nil then qType = cd.q[15] end
				if cd.q[16] ~= nil then qHC = cd.q[16] end
				if cd.q[17] ~= nil then qBuffName = cd.q[17] end
				if cd.q[18] ~= nil then qObjName = cd.q[18] end
				--w Handler
				if cd.w[10] ~= nil then wRange = cd.w[10] end
				if cd.w[11] ~= nil then wSpeed = cd.w[11] end
				if cd.w[12] ~= nil then wDelay = cd.w[12] end
				if cd.w[13] ~= nil then wWidth = cd.w[13] end
				if cd.w[14] ~= nil then wCol = cd.w[14] end
				if cd.w[15] ~= nil then wType = cd.w[15] end
				if cd.w[16] ~= nil then wHC = cd.w[16] end
				if cd.w[17] ~= nil then wBuffName = cd.w[17] end
				if cd.w[18] ~= nil then wObjName = cd.w[18] end
				--e Handler
				if cd.e[10] ~= nil then eRange = cd.e[10] end
				if cd.e[11] ~= nil then eSpeed = cd.e[11] end
				if cd.e[12] ~= nil then eDelay = cd.e[12] end
				if cd.e[13] ~= nil then eWidth = cd.e[13] end
				if cd.e[14] ~= nil then eCol = cd.e[14] end
				if cd.e[15] ~= nil then eType = cd.e[15] end
				if cd.e[16] ~= nil then eHC = cd.e[16] end
				if cd.e[17] ~= nil then eBuffName = cd.e[17] end
				if cd.e[18] ~= nil then eObjName = cd.e[18] end
				--r Handler
				if cd.r[10] ~= nil then rRange = cd.r[10] end
				if cd.r[11] ~= nil then rSpeed = cd.r[11] end
				if cd.r[12] ~= nil then rDelay = cd.r[12] end
				if cd.r[13] ~= nil then rWidth = cd.r[13] end
				if cd.r[14] ~= nil then rCol = cd.r[14] end
				if cd.r[15] ~= nil then rType = cd.r[15] end
				if cd.r[16] ~= nil then rHC = cd.r[16] end
				if cd.r[17] ~= nil then rBuffName = cd.r[17] end
				if cd.r[18] ~= nil then rObjName = cd.r[18] end
			end
			if cd.AA[7] ~= nil then
				BuffedRange = cd.AA[4]
				BuffedSpell = cd.AA[7]
				BuffedDuration = (cd.AA[8] * 1000)
			end
			if FakeLoad == false then
				--PrintFloatText(myHero, 1, "Refreshing Data")
			end
			break
		end
	end
end
function Load()
	PrintChat("AIOCombo: Loaded")
	VP = VPrediction()
	Prodict = ProdictManager.GetInstance()
	PersonalLoadOnce = false
	CastOnceFS = false
	delayVar = false
	BuffedVar = false
	closestAlly = nil
    currentAlly = nil
	qObjVar,wObjVar,eObjVar,rObjVar = false,false,false,false
	qBuffVar,wBuffVar,eBuffVar,rBuffVar = false,false,false,false
	delay = GetTickCount()
	delay2 = GetTickCount()
	RefreshTable()
	--AFKLoader()
	
	Menu = scriptConfig("AIOCombo: "..versionGOE,"AIOC")
	Menu:addParam("AIOComboINFO", "[AIOCombo]", SCRIPT_PARAM_INFO, "")
	
	Menu:addSubMenu("AIOCombo: General", "General")
	Menu.General:addParam("Combo", "Activate Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu.General:addParam("Harass", "Activate Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
	
	Menu:addSubMenu("AIOCombo: Combo Skills", "Skills")
	Menu.Skills:addParam("PF", "[Primary Form]", SCRIPT_PARAM_INFO, "[PF]")
	if qCombo ~= nil then Menu.Skills:addParam("autoQ", "Automatically use Q", SCRIPT_PARAM_ONOFF, true) end
	if wCombo ~= nil then Menu.Skills:addParam("autoW", "Automatically use W", SCRIPT_PARAM_ONOFF, true) end
	if eCombo ~= nil then Menu.Skills:addParam("autoE", "Automatically use E", SCRIPT_PARAM_ONOFF, true) end
	if rCombo ~= nil then Menu.Skills:addParam("autoR", "Automatically use R", SCRIPT_PARAM_ONOFF, true) end
	Menu.Skills:addParam("SF", "[Secondary Form]", SCRIPT_PARAM_INFO, "[SF]") 
	if qCombo2 ~= nil then Menu.Skills:addParam("autoQ2", "Automatically use Q", SCRIPT_PARAM_ONOFF, true) end
	if wCombo2 ~= nil then Menu.Skills:addParam("autoW2", "Automatically use W", SCRIPT_PARAM_ONOFF, true) end
	if eCombo2 ~= nil then Menu.Skills:addParam("autoE2", "Automatically use E", SCRIPT_PARAM_ONOFF, true) end
	if rCombo2 ~= nil then Menu.Skills:addParam("autoR2", "Automatically use R", SCRIPT_PARAM_ONOFF, true) end
	Menu.Skills:addParam("MISC", "[MISC]", SCRIPT_PARAM_INFO, "[MISC]")
	Menu.Skills:addParam("Packets", "Packet Casting (Broken)", SCRIPT_PARAM_ONOFF, false)
	Menu.Skills:addParam("PacketsInfo", "Overide Spell Packet Blocking? (Broken)", SCRIPT_PARAM_INFO, "")
	
	Menu:addSubMenu("AIOCombo: Harass Skills", "Harass")
	Menu.Harass:addParam("PF", "[Primary Form]", SCRIPT_PARAM_INFO, "[PF]")
	if qHarass ~= nil then Menu.Harass:addParam("autoQH", "Automatically harass with Q", SCRIPT_PARAM_ONOFF, true) end
	if wHarass ~= nil then Menu.Harass:addParam("autoWH", "Automatically harass with W", SCRIPT_PARAM_ONOFF, true) end
	if eHarass ~= nil then Menu.Harass:addParam("autoEH", "Automatically harass with E", SCRIPT_PARAM_ONOFF, true) end
	if rHarass ~= nil then Menu.Harass:addParam("autoRH", "Automatically harass with R", SCRIPT_PARAM_ONOFF, true) end
	Menu.Harass:addParam("SF", "[Secondary Form]", SCRIPT_PARAM_INFO, "[SF]")
	if qHarass2 ~= nil then Menu.Harass:addParam("autoQH2", "Automatically harass with Q", SCRIPT_PARAM_ONOFF, true) end
	if wHarass2 ~= nil then Menu.Harass:addParam("autoWH2", "Automatically harass with W", SCRIPT_PARAM_ONOFF, true) end
	if eHarass2 ~= nil then Menu.Harass:addParam("autoEH2", "Automatically harass with E", SCRIPT_PARAM_ONOFF, true) end
	if rHarass2 ~= nil then Menu.Harass:addParam("autoRH2", "Automatically harass with R", SCRIPT_PARAM_ONOFF, true) end
	
	if Protect == true then
		Menu.General:addParam("Protect", "Activate Protect", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("A"))
		Menu:addSubMenu("AIOCombo: Protect Skills", "Protect")
		Menu.Protect:addParam("PF", "[Primary Form]", SCRIPT_PARAM_INFO, "[PF]")
		if qProtect == true then Menu.Protect:addParam("qProtect", "Automatically protect with Q", SCRIPT_PARAM_ONOFF, true) end
		if wProtect == true then Menu.Protect:addParam("wProtect", "Automatically protect with W", SCRIPT_PARAM_ONOFF, true) end
		if eProtect == true then Menu.Protect:addParam("eProtect", "Automatically protect with E", SCRIPT_PARAM_ONOFF, true) end
		if rProtect == true then Menu.Protect:addParam("rProtect", "Automatically protect with R", SCRIPT_PARAM_ONOFF, true) end
		Menu.Protect:addParam("SF", "[Secondary Form]", SCRIPT_PARAM_INFO, "[SF]") 
		if qProtect2 == true then Menu.Protect:addParam("qProtect2", "Automatically protect with Q", SCRIPT_PARAM_ONOFF, true) end
		if wProtect2 == true then Menu.Protect:addParam("wProtect2", "Automatically protect with W", SCRIPT_PARAM_ONOFF, true) end
		if eProtect2 == true then Menu.Protect:addParam("eProtect2", "Automatically protect with E", SCRIPT_PARAM_ONOFF, true) end
		if rProtect2 == true then Menu.Protect:addParam("rProtect2", "Automatically protect with R", SCRIPT_PARAM_ONOFF, true) end
	end
	
	Menu:addSubMenu("AIOCombo: Prediction", "Prediction")
	Menu.Prediction:addParam("Type", "", SCRIPT_PARAM_ONOFF, true)
	Menu.Prediction:addParam("TypeInfo", "Type of Prediction to use: ", SCRIPT_PARAM_INFO, "")
		
	Menu:addSubMenu("AIOCombo: VPrediction Hit Chances", "HitChance")
	if qType == "Line" or qType == "Circular" or qType == "Still" then
	Menu.HitChance:addParam("qHC", "Hit Chance for spell: Q", SCRIPT_PARAM_SLICE, 2,0,5,0) 
	Menu.HitChance:addParam("qHCinfo", "Will use Q if: ", SCRIPT_PARAM_INFO, qHCvar)
	if qHitChance ~= nil then Menu.HitChance.qHC = qHitChance end
	end
	if wType == "Line" or wType == "Circular" or wType == "Still" then
	Menu.HitChance:addParam("wHC", "Hit Chance for spell: W", SCRIPT_PARAM_SLICE, 2,0,5,0)
	Menu.HitChance:addParam("wHCinfo", "Will use W if: ", SCRIPT_PARAM_INFO, wHCvar)
	if wHitChance ~= nil then Menu.HitChance.wHC = wHitChance end
	end
	if eType == "Line" or eType == "Circular" or eType == "Still" then
	Menu.HitChance:addParam("eHC", "Hit Chance for spell: E", SCRIPT_PARAM_SLICE, 2,0,5,0) 
	Menu.HitChance:addParam("eHCinfo", "Will use E if: ", SCRIPT_PARAM_INFO, eHCvar)
	if eHitChance ~= nil then Menu.HitChance.eHC = eHitChance end
	end
	if rType == "Line" or rType == "Circular" or rType == "Still" then
	Menu.HitChance:addParam("rHC", "Hit Chance for spell: R", SCRIPT_PARAM_SLICE, 2,0,5,0)
	Menu.HitChance:addParam("rHCinfo", "Will use R if: ", SCRIPT_PARAM_INFO, rHCvar)
	if rHitChance ~= nil then Menu.HitChance.rHC = rHitChance end
	end
	
	Menu:addSubMenu("AIOCombo: Drawing", "Draw")
	Menu.Draw:addParam("ReloadingDRAW", "[You will have to reload your script to view changes]", SCRIPT_PARAM_INFO,"")
	Menu.Draw:addParam("showCombo","Show 'Activate Combo'", SCRIPT_PARAM_ONOFF, true)
	Menu.Draw:addParam("showHarass","Show 'Activate Harass'", SCRIPT_PARAM_ONOFF, true)
	if Protect == true then Menu.Draw:addParam("showProtect","Show 'Activate Protect'", SCRIPT_PARAM_ONOFF, true) end
	
	Menu.Draw:addParam("showPacketInfo", "Show 'Override Spell Packet Blocking?'", SCRIPT_PARAM_ONOFF, true)
	Menu.Draw:addParam("showPrediction", "Show 'Type of Prediction to use'", SCRIPT_PARAM_ONOFF, true)
	
		if Menu.Draw.showPacketInfo then
			Menu.Skills:permaShow("PacketsInfo")
		end
		if Menu.Draw.showPrediction then
			Menu.Prediction:permaShow("TypeInfo")
		end
		if Menu.Draw.showCombo then
			Menu.General:permaShow("Combo")
		end
		if Menu.Draw.showHarass then
			Menu.General:permaShow("Harass")
		end
		if Protect == true and Menu.Draw.showProtect then
			Menu.General:permaShow("Protect")
		end
		
		
	if myHero.charName == "Thresh" then
		Menu:addSubMenu("AIOCombo: Personal", "Personal")
		Menu.Draw:addParam("showeUsage", "Show 'How would you like to use E'", SCRIPT_PARAM_ONOFF, true)
			Menu.Personal:addParam("eUsageInfo", "How would you like to use E: ", SCRIPT_PARAM_INFO, "")
			Menu.Personal:addParam("eUsage", "", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("T"))
		if Menu.Draw.showeUsage == true then	
			Menu.Personal:permaShow("eUsageInfo")
		end
	end
	
	--[[Menu:addSubMenu("AIOCombo: Updater", "Updater")
	Menu.Updater:addParam("update", "Run the Updater (Press Once)", SCRIPT_PARAM_ONOFF, false)]]
	
	if Menu.Draw.showPacketInfo or Menu.Draw.showPrediction or Menu.Draw.showCombo or Menu.Draw.showHarass or Menu.Draw.showProtect or Menu.Draw.showeUsage then Menu:permaShow("AIOComboINFO") end
	
	ts = TargetSelector(TARGET_NEAR_MOUSE, 1750, TSDamageType, false)
	ts.name = myHero.charName
	Menu:addTS(ts)
	FakeLoad = false
end
function OnLoad()
	if PressLoad == true then
		Load()
		PressLoad = false
	end
end
function OnTick()
	if PissOff == true or EmergencyStop == true then return end
	if FakeLoad == false then
		Misc()
		if ComboVar ~= nil then
			_ENV[ComboVar]()
		end
	end
end

function OnDraw()
	if PissOff == true or EmergencyStop == true then return end
	if FakeLoad == false then
		

	end
end
function Misc()
	ts:update()
	Target = ts.target
	if Menu.Skills.Packets == true then Menu.Skills.PacketsInfo = "Yes" else Menu.Skills.PacketsInfo = "No" end
	if CastOnceFS == false then CastOnce() end
	if GetTickCount() - delay > 250 and delayVar then
		RefreshTable()
		delayVar = false
	end
	if BuffedDuration ~= nil then
		if GetTickCount() - delay2 < BuffedDuration and BuffedVar then
			AARange = BuffedRange
		else
			RefreshTable()
			BuffedVar = false
		end
	end
	BuffCheck()
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	WREADY = (myHero:CanUseSpell(_W) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)
	if Menu.HitChance.qHC == 1 then 
		Menu.HitChance.qHCinfo = "Low Hit Chance"
	elseif Menu.HitChance.qHC == 2 then
		Menu.HitChance.qHCinfo = "High Hit Chance"
	elseif Menu.HitChance.qHC == 3 then
		Menu.HitChance.qHCinfo = "Target Slow/Close"
	elseif Menu.HitChance.qHC == 4 then
		Menu.HitChance.qHCinfo = "Target Immobilised"
	elseif Menu.HitChance.qHC == 5 then
		Menu.HitChance.qHCinfo = "Target Dashing"
	elseif Menu.HitChance.qHC == 0 then
		Menu.HitChance.qHCinfo = "No Waypoints Found"
	end
	if Menu.HitChance.wHC == 1 then 
		 Menu.HitChance.wHCinfo = "Low Hit Chance"
	elseif Menu.HitChance.wHC == 2 then
		Menu.HitChance.wHCinfo = "High Hit Chance"
	elseif Menu.HitChance.wHC == 3 then
		Menu.HitChance.wHCinfo = "Target Slow/Close"
	elseif Menu.HitChance.wHC == 4 then
		Menu.HitChance.wHCinfo = "Target Immobilised"
	elseif Menu.HitChance.wHC == 5 then
		Menu.HitChance.wHCinfo = "Target Dashing"
	elseif Menu.HitChance.wHC == 0 then
		Menu.HitChance.wHCinfo = "No Waypoints Found"
	end
	if Menu.HitChance.eHC == 0 then
		Menu.HitChance.eHCinfo = "No Waypoints Found"
	elseif Menu.HitChance.eHC == 1 then 
		Menu.HitChance.eHCinfo = "Low Hit Chance"
	elseif Menu.HitChance.eHC == 2 then
		Menu.HitChance.eHCinfo = "High Hit Chance"
	elseif Menu.HitChance.eHC == 3 then
		Menu.HitChance.eHCinfo = "Target Slow/Close"
	elseif Menu.HitChance.eHC == 4 then
		Menu.HitChance.eHCinfo = "Target Immobilised"
	elseif Menu.HitChance.eHC == 5 then
		Menu.HitChance.eHCinfo = "Target Dashing"
	end
	if Menu.HitChance.rHC == 1 then 
		Menu.HitChance.rHCinfo = "Low Hit Chance"
	elseif Menu.HitChance.rHC == 2 then
		Menu.HitChance.rHCinfo = "High Hit Chance"
	elseif Menu.HitChance.rHC == 3 then
		Menu.HitChance.rHCinfo = "Target Slow/Close"
	elseif Menu.HitChance.rHC == 4 then
		Menu.HitChance.rHCinfo = "Target Immobilised"
	elseif Menu.HitChance.rHC == 5 then
		Menu.HitChance.rHCinfo = "Target Dashing"
	elseif Menu.HitChance.rHC == 0 then
		Menu.HitChance.rHCinfo = "No Waypoints Found"
	end
	if Menu.Prediction.Type == true then
		Menu.Prediction.TypeInfo = "VPrediction"
	else
		Menu.Prediction.TypeInfo = "Prodiction"
	end
	
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		if currentAlly.team == myHero.team and not currentAlly.dead and currentAlly.charName ~= myHero.charName then
			if closestAlly == nil then
				closestAlly = currentAlly
			elseif GetDistance(currentAlly) < GetDistance(closestAlly) then
				closestAlly = currentAlly
			end
		end
	end
	
	--[[if Menu.Updater.update then
		UpdateLoad()
		Menu.Updater.update = false
	end]]
end
function CastOnce()
	if qType == "CastOnce" and myHero:CanUserSpell(_Q) == READY then
		CastSpell(_Q)
		CastOnceFS = true
	end
	if wType == "CastOnce" and myHero:CanUserSpell(_W) == READY then
		CastSpell(_W)
		CastOnceFS = true
	end
	if eType == "CastOnce" and myHero:CanUserSpell(_E) == READY then
		CastSpell(_E)
		CastOnceFS = true
	end
	if rType == "CastOnce" and myHero:CanUserSpell(_R) == READY then
		CastSpell(_R)
		CastOnceFS = true
	end
end
function OnProcessSpell(unit, spell)
	if unit.isMe then
		delay = GetTickCount()
		delayVar = true
		if BuffedSpell ~= nil then
			if spell.name == myHero:GetSpellData(BuffedSpell).name then
				delay2 = GetTickCount()
				BuffedVar = true
			end
		end
	end
end
function FormCheck()
	if myHero.charName == "Elise" then
		if myHero:GetSpellData(_Q).name == "EliseHumanQ" then
			PrimaryForm = true
		else
			PrimaryForm = false
		end
	elseif myHero.charName == "Jayce" then
		if myHero:GetSpellData(_Q).name == "JayceToTheSkies" then
			PrimaryForm = true
		else
			PrimaryForm = false
		end
	elseif myHero.charName == "Nidalee" then
		if myHero:GetSpellData(_Q).name == "JavelinToss" then
			PrimaryForm = true
		else
			PrimaryForm = false
		end
	elseif myHero.charName == "Quinn" then
		if myHero:GetSpellData(_Q).name == "QuinnQ" then
			PrimaryForm = true
		else
			PrimaryForm = false
		end
	else
		PrimaryForm = true
	end
end
function BuffCheck()
	for i = 1, myHero.buffCount do
        local tBuff = myHero:getBuff(i)
        if BuffIsValid(tBuff) then
			if qBuffName ~= nil and tBuff.name == qBuffName then
				qBuffVar = true
			else
				qBuffVar = false
			end
			if wBuffName ~= nil and tBuff.name == wBuffName then
				wBuffVar = true
			else
				wBuffVar = false
			end
			if eBuffName ~= nil and tBuff.name == eBuffName then
				eBuffVar = true
			else
				eBuffVar = false
			end
			if rBuffName ~= nil and tBuff.name == rBuffName then
				rBuffVar = true
			else
				rBuffVar = false
			end
        end
    end
end
function OnCreateObj(obj)
	if qObjName ~= nil and obj.name == qObjName then
		qObjVar = true
		qObjID = obj
	end
	if wObjName ~= nil and obj.name == wObjName then
		wObjVar = true
		wObjID = obj
	end
	if eObjName ~= nil and obj.name == eObjName then
		eObjVar = true
		eObjID = obj
	end
	if rObjName ~= nil and obj.name == rObjName then
		rObjVar = true
		rObjID = obj
	end
end
function OnDeleteObj(obj)
	if qObjName ~= nil and obj.name == qObjName then
		qObjVar = false
		qObjID = nil
	end
	if wObjName ~= nil and obj.name == wObjName then
		wObjVar = false
		wObjID = nil
	end
	if eObjName ~= nil and obj.name == eObjName then
		eObjVar = false
		eObjID = nil
	end
	if rObjName ~= nil and obj.name == rObjName then
		rObjVar = false
		rObjID = nil
	end
end
function Blitzcrank()
	if PersonalLoadOnce == false then
		ProdictQ = Prodict:AddProdictionObject(_Q, qRange, qSpeed, qDelay, qWidth)
		ProdictR = Prodict:AddProdictionObject(_R, rRange, math.huge, rDelay, rWidth)
		ProdictQCol = Collision(qRange,qSpeed,qDelay,qWidth)
		PersonalLoadOnce = true
	end
	if Menu.General.Combo then
		if Target then
			if Menu.Skills.autoQ and QREADY then
				if Menu.Prediction.Type == true then
					qCastPosition,  qHitChance,  qPosition = VP:GetLineCastPosition(Target, qDelay, qWidth, qRange, qSpeed, myHero)
					if (qHitChance >= Menu.HitChance.qHC or qHitChance == 0) and (GetDistance(qCastPosition) <= qRange) then
						local willCollide = ProdictQCol:GetMinionCollision(myHero, qCastPosition)
						if not willCollide then
							if Menu.Skills.Packets then
								Packet('S_CAST', { spellId = 128, toX = qCastPosition.x, toY = qCastPosition.z }):send() 
							else
								CastSpell(_Q, qCastPosition.x, qCastPosition.z)
							end
						end
					end
				elseif Menu.Prediction.Type == false then
					ProdictQ:GetPredictionCallBack(Target,ProdictCastQ,myHero)
				end
			end
			if Menu.Skills.autoW and WREADY then
				if GetDistance(Target) < wRange then
					CastSpell(_W)
				end
			end
			if Menu.Skills.autoE and EREADY then
				if GetDistance(Target) <= eRange then
					CastSpell(_E)
				end
			end
			if Menu.Skills.autoR and (RREADY and not EREADY)then
				if Menu.Prediction.Type == true then
					rCastPosition, rHitChance = VP:GetPredictedPos(Target,rDelay)
					if (rHitChance >= Menu.HitChance.rHC or rHitChance == 0) and (GetDistance(rCastPosition) <= rRange) then
						if Menu.Skills.Packets then
							Packet('S_CAST', { spellId = 131, toX = rCastPosition.x, toY = rCastPosition.z }):send() 
						else
							CastSpell(_R,rCastPosition.x,rCastPosition.z)
						end
					end
				elseif Menu.Prediction.Type == false then	
					ProdictR:GetPredictionCallBack(Target,ProdictCastR,myHero)
				end
			end
		end		
	elseif Menu.General.Harass then
		if Target then
			if Menu.Skills.autoQH and QREADY then
				if Menu.Prediction.Type == true then
					qCastPosition,  qHitChance,  qPosition = VP:GetLineCastPosition(Target, qDelay, qWidth, qRange, qSpeed, myHero)
					if (qHitChance >= Menu.HitChance.qHC or qHitChance == 0) and (GetDistance(qCastPosition) <= qRange) then
						local willCollide = ProdictQCol:GetMinionCollision(myHero, qCastPosition)
						if not willCollide then
							if Menu.Skills.Packets then
								Packet('S_CAST', { spellId = 128, toX = qCastPosition.x, toY = qCastPosition.z }):send() 
							else
								CastSpell(_Q, qCastPosition.x, qCastPosition.z)
							end
						end
					end
				elseif Menu.Prediction.Type == false then
					ProdictQ:GetPredictionCallBack(Target,ProdictCastQ,myHero)
				end
			end
			if Menu.Skills.autoRH and RREADY then
				if Menu.Prediction.Type == true then
					rCastPosition, rHitChance = VP:GetPredictedPos(Target,rDelay)
					if (rHitChance >= Menu.HitChance.rHC or rHitChance == 0) and (GetDistance(rCastPosition) <= rRange) then
						if Menu.Skills.Packets then
							Packet('S_CAST', { spellId = 131, toX = rCastPosition.x, toY = rCastPosition.z }):send() 
						else
							CastSpell(_R,rCastPosition.x,rCastPosition.z)
						end
					end
				elseif Menu.Prediction.Type == false then	
					ProdictR:GetPredictionCallBack(Target,ProdictCastR,myHero)
				end
			end
		end
	end
end
function Janna()
	if PersonalLoadOnce == false then
		ProdictQ = Prodict:AddProdictionObject(_Q, qRange, qSpeed, qDelay, qWidth)
		PersonalLoadOnce = true
	end
	if Menu.General.Protect then
		if EREADY then
			if closestAlly ~= nil then 
				if (GetDistance(closestAlly) < eRange) and Menu.Protect.eProtect then
					CastSpell(_E, closestAlly)
				end
			end
		end
		if RREADY then
			if closestAlly ~= nil then
				if (GetDistance(closestAlly) < rRange) and Menu.Protect.rProtect then
					CastSpell(_R, closestAlly)
				end
			end
		end
	end
	if Menu.General.Combo then
		
	elseif Menu.General.Harass then
	
	end
end
function Karma()
	if Menu.General.Protect then
		if EREADY then
			if closestAlly ~= nil then 
				if (GetDistance(closestAlly) < eRange) and Menu.Protect.eProtect then
					CastSpell(_E, closestAlly)
				end
			end
		end
	end
	if Menu.General.Combo then
		
	elseif Menu.General.Harass then
	
	end
end
function Leona()
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Lulu()
	if Menu.General.Protect then
		if EREADY then
			if closestAlly ~= nil then 
				if (GetDistance(closestAlly) < eRange) and Menu.Protect.eProtect then
					CastSpell(_E, closestAlly)
				end
			end
		end
		if RREADY then
			if closestAlly ~= nil then
				if (GetDistance(closestAlly) < rRange) and Menu.Protect.rProtect then
					CastSpell(_R, closestAlly)
				end
			end
		end
	end
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Morgana()
	if Menu.General.Protect then
		if EREADY then
			if closestAlly ~= nil then 
				if (GetDistance(closestAlly) < eRange) and Menu.Protect.eProtect then
					CastSpell(_E, closestAlly)
				end
			end
		end
	end
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Nami()
	if Menu.General.Protect then
		if EREADY then
			if closestAlly ~= nil then 
				if (GetDistance(closestAlly) < eRange) and Menu.Protect.eProtect then
					CastSpell(_E, closestAlly)
				end
			end
		end
		if WREADY then
			if closestAlly ~= nil then
				if (GetDistance(closestAlly) < wRange) and Menu.Protect.wProtect then
					CastSpell(_W, closestAlly)
				end
			end
		end
	end
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Nidalee()
	if Menu.General.Protect then
		if EREADY and PrimaryForm then
			if closestAlly ~= nil then 
				if (GetDistance(closestAlly) < eRange) and Menu.Protect.eProtect then
					CastSpell(_E, closestAlly)
				end
			end
		end
	end
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Sona()
	if Menu.General.Protect then
		if EREADY then
			if closestAlly ~= nil then 
				if (GetDistance(closestAlly) < eRange) and Menu.Protect.eProtect then
					CastSpell(_E)
				end
			end
		end
	end
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Soraka()
	if Menu.General.Protect then
		if WREADY then
			if closestAlly ~= nil then
				if (GetDistance(closestAlly) < wRange) and Menu.Protect.wProtect then
					CastSpell(_W, closestAlly)
				end
			end
		end
		if EREADY then
			if closestAlly ~= nil then 
				if (GetDistance(closestAlly) < eRange) and Menu.Protect.eProtect then
					CastSpell(_E, closestAlly)
				end
			end
		end
		if RREADY then
			if closestAlly ~= nil then
				if (GetDistance(closestAlly) < 10000) and Menu.Protect.rProtect and (closestAlly.health/cloestAlly.maxHealth) < 0.60 then
					CastSpell(_R, closestAlly)
				end
			end
		end
	end
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Thresh()
	if PersonalLoadOnce == false then
		ProdictQ = Prodict:AddProdictionObject(_Q, qRange, qSpeed, qDelay, qWidth)
		ProdictE = Prodict:AddProdictionObject(_E, eRange, math.huge, eDelay, eWidth)
		ProdictR = Prodict:AddProdictionObject(_R, rRange, math.huge, rDelay, rWidth)
		ProdictQCol = Collision(qRange,qSpeed,qDelay,qWidth)
		PersonalLoadOnce = true
	end
	if Menu.General.Protect then
		if WREADY then
			if closestAlly ~= nil then 
				if (GetDistance(closestAlly) < wRange) and Menu.Protect.wProtect then
					CastSpell(_W, closestAlly.x, closestAlly.z)
				end
			end
		end
	end
	if Menu.General.Combo then
		if Target then
			if Menu.Skills.autoQ and QREADY then
				if Menu.Prediction.Type == true then
					qCastPosition,  qHitChance,  qPosition = VP:GetLineCastPosition(Target, qDelay, qWidth, qRange, qSpeed, myHero)
					if (qHitChance >= Menu.HitChance.qHC or qHitChance == 0) and (GetDistance(qCastPosition) <= qRange) then
						local willCollide = ProdictQCol:GetMinionCollision(myHero, qCastPosition)
						if not willCollide then
							if Menu.Skills.Packets then
								Packet('S_CAST', { spellId = 128, toX = qCastPosition.x, toY = qCastPosition.z }):send() 
							else
								CastSpell(_Q, qCastPosition.x, qCastPosition.z)
							end
						end
					end
				elseif Menu.Prediction.Type == false then
					ProdictQ:GetPredictionCallBack(Target,ProdictCastQ,myHero)
				end
			end
			if Menu.Skills.autoE and EREADY then
				if Menu.Prediction.Type == true then
					if Menu.Personal.eUsage == true then
						eCastPosition,  eHitChance, ePosition = VP:GetCircularCastPosition(Target, eDelay, eWidth, eRange)
						xPos = myHero.x + (myHero.x - eCastPosition.x)
						zPos = myHero.z + (myHero.z - eCastPosition.z)
						if (eHitChance >= Menu.HitChance.eHC or eHitChance == 0) and (GetDistance(eCastPosition) <= eRange) then
							if Menu.Skills.Packets then
								Packet('S_CAST', { spellId = 130, toX = xPos, toY = zPos }):send() 
							else
								CastSpell(_E, xPos, zPos)
							end
						end
					else
						eCastPosition,  eHitChance,  ePosition = VP:GetCircularCastPosition(Target, eDelay, eWidth, eRange)
						if (eHitChance >= Menu.HitChance.eHC or eHitChance == 0) and (GetDistance(eCastPosition) <= eRange) then
							if Menu.Skills.Packets then
								Packet('S_CAST', { spellId = 130, toX = eCastPosition.x, toY = eCastPosition.z }):send() 
							else
								CastSpell(_E, eCastPosition.x, eCastPosition.z)
							end
						end
					end
				
				elseif Menu.Prediction.Type == false then
					if Menu.Personal.eUsage == true then
						ProdictE:GetPredictionCallBack(Target,ThreshCastE,myHero)
					else
						ProdictE:GetPredictionCallBack(Target,ProdictCastE,myHero)
					end
				end
			end
			if Menu.Skills.autoR and RREADY then
				if Menu.Prediction.Type == true then
					rCastPosition, rHitChance = VP:GetPredictedPos(Target,rDelay)
					if (rHitChance >= Menu.HitChance.rHC or rHitChance == 0) and (GetDistance(rCastPosition) <= rRange) then
						if Menu.Skills.Packets then
							Packet('S_CAST', { spellId = 131, toX = rCastPosition.x, toY = rCastPosition.z }):send() 
						else
							CastSpell(_R,rCastPosition.x,rCastPosition.z)
						end
					end
				elseif Menu.Prediction.Type == false then	
					ProdictR:GetPredictionCallBack(Target,ProdictCastR,myHero)
				end
			end
		end
	elseif Menu.General.Harass then
		if Target then
			if Menu.Harass.autoEH and EREADY then
				if Menu.Prediction.Type == true then
					if Menu.Personal.eUsage == true then
						eCastPosition,  eHitChance, ePosition = VP:GetCircularCastPosition(Target, eDelay, eWidth, eRange)
						xPos = myHero.x + (myHero.x - eCastPosition.x)
						zPos = myHero.z + (myHero.z - eCastPosition.z)
						if (eHitChance >= Menu.HitChance.eHC or eHitChance == 0) and (GetDistance(eCastPosition) <= eRange) then
							if Menu.Skills.Packets then
								Packet('S_CAST', { spellId = 130, toX = xPos, toY = zPos }):send() 
							else
								CastSpell(_E, xPos, zPos)
							end
						end
					else
						eCastPosition,  eHitChance,  ePosition = VP:GetCircularCastPosition(Target, eDelay, eWidth, eRange)
						if (eHitChance >= Menu.HitChance.eHC or eHitChance == 0) and (GetDistance(eCastPosition) <= eRange) then
							if Menu.Skills.Packets then
								Packet('S_CAST', { spellId = 130, toX = eCastPosition.x, toY = eCastPosition.z }):send() 
							else
								CastSpell(_E, eCastPosition.x, eCastPosition.z)
							end
						end
					end
				elseif Menu.Prediction.Type == false then
					if Menu.Personal.eUsage == true then
						ProdictE:GetPredictionCallBack(Target,ThreshCastE,myHero)
					else
						ProdictE:GetPredictionCallBack(Target,ProdictCastE,myHero)
					end
				end
			end
		end
	end	
	if Menu.Personal.eUsage == true then
		Menu.Personal.eUsageInfo = "To Pull"	
	else
		Menu.Personal.eUsageInfo = "To Push"
	end
end
function Zyra()
	if Menu.General.Combo then
		if Target then
			if qObjID ~= nil then
				if WREADY then 
					CastSpell(_W,qObjID.x,qObjID.z)
					--[[alpha = math.atan(math.abs(Target.z-myHero.z)/math.abs(Target.x-myHero.x))
					locX = math.cos(alpha)*wRange
					locZ = math.sin(alpha)*wRange
					CastSpell(_W, math.sign(Target.x-myHero.x)*locX+myHero.x, math.sign(Target.z-myHero.z)*locZ+myHero.z) ]]
				end
			end
		end
	elseif Menu.General.Harass then
		if Target then
			
		end
	end
end
function ProdictCastQ(unit,pos,spell)
	if GetDistance(pos) < qRange then
		if qCol == true then
			local willCollide = ProdictQCol:GetMinionCollision(myHero, pos)
			if not willCollide then
				if Menu.Skills.Packets then
					Packet('S_CAST', { spellId = 128, toX = pos.x, toY = pos.z }):send()
				else
					CastSpell(_Q,pos.x,pos.z)
				end
			end
		elseif qCol == false then
			if Menu.Skills.Packets then
				Packet('S_CAST', { spellId = 128, toX = pos.x, toY = pos.z }):send()
			else
				CastSpell(_Q,pos.x,pos.z)
			end
		end
	end
end
function ProdictCastW(unit,pos,spell)
	if GetDistance(pos) < wRange then
		if wCol == true then
			local willCollide = ProdictWCol:GetMinionCollision(myHero, pos)
			if not willCollide then
				if Menu.Skills.Packets then
					Packet('S_CAST', { spellId = 129, toX = pos.x, toY = pos.z }):send()
				else
					CastSpell(_W,pos.x,pos.z)
				end
			end
		elseif wCol == false then
			if Menu.Skills.Packets then
				Packet('S_CAST', { spellId = 129, toX = pos.x, toY = pos.z }):send()
			else
				CastSpell(_W,pos.x,pos.z)
			end
		end
	end
end
function ProdictCastE(unit,pos,spell)
	if GetDistance(pos) < qRange then
		if eCol == true then
			local willCollide = ProdictECol:GetMinionCollision(myHero, pos)
			if not willCollide then
				if Menu.Skills.Packets then
					Packet('S_CAST', { spellId = 130, toX = pos.x, toY = pos.z }):send()
				else
					CastSpell(_E,pos.x,pos.z)
				end
			end
		elseif eCol == false then
			if Menu.Skills.Packets then
				Packet('S_CAST', { spellId = 130, toX = pos.x, toY = pos.z }):send()
			else
				CastSpell(_E,pos.x,pos.z)
			end
		end
	end
end
function ThreshCastE(unit,pos,spell)
	if GetDistance(pos) < eRange then
		xPos = myHero.x + (myHero.x - pos.x)
		zPos = myHero.z + (myHero.z - pos.z)
		if Menu.Skills.Packets then
			Packet('S_CAST', { spellId = 130, toX = xPos, toY = zPos }):send()
		else
			CastSpell(_E,xPos,zPos)
		end
	end
end
function ProdictCastR(unit,pos,spell)
	if GetDistance(pos) < rRange then
		if rCol == true then
			local willCollide = ProdictRCol:GetMinionCollision(myHero, pos)
			if not willCollide then
				if Menu.Skills.Packets then
					Packet('S_CAST', { spellId = 131, toX = pos.x, toY = pos.z }):send()
				else
					CastSpell(_R,pos.x,pos.z)
				end
			end	
		elseif rCol == false then
			if Menu.Skills.Packets then
				Packet('S_CAST', { spellId = 131, toX = pos.x, toY = pos.z }):send()
			else
				CastSpell(_R,pos.x,pos.z)
			end
		end
	end
end
function math.sign(x)
 if x < 0 then
  return -1
 elseif x > 0 then
  return 1
 else
  return 0
 end
end