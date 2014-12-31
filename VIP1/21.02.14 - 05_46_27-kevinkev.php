<?php exit() ?>--by kevinkev 27.253.95.57
--[[
$Name: AIOCombo
$Version: 1
$Author: Pain
$Credits:
$Information: 

$Current Supported Champions:
	"NAME" - SPELL
--]]
--require 'VPrediction'
--require 'Collision'

function UpdateLoad()
	--[[versionGOE = 0.001
	Update = UpdateLib("AIOCombo Test")
	Update.LocalVersion = versionGOE
	Update.SCRIPT_NAME = "AIOCombo Test"
	Update.SCRIPT_URL = "https://bitbucket.org/BoLPain/private-scripts/raw/master/AIOSupport.lua"
	Update.PATH = BOL_PATH.."Scripts\\".."AIOCombo_Test.lua"
	Update.HOST = "painenterprises.cuccfree.com"
	Update.URL_PATH = "/Scripts/Revisions/AIOSupport.lua"
	Update:Run()	]]
	print("Updated")
end
PissOff = true
EmergencyStop = false
print("Gathering Data, please do not press f9.")
GetAsyncWebResult(
"painenterprises.cuccfree.com",
"/pain/aiocombo/auth.php",
"aioc="..GetUser(),
function(msg) 
	print("Data has been gathered.")
	if msg == "authed" then 
		FakeLoad = true
		PissOff = false
		print("AIOCombo: Accepted")
		Load()
	else
		PissOff = true
		print("AIOCombo: Denied")
	end 
end)
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
1 = spellRange (Range)
2 = spellSpeed (Speed)
3 = spellDelay (Delay)
4 = spellWidth (Width)
5 = spellCol (Collision)
6 = spellType (Spell Type: Line, Circular, Still, Self, Heal, Target)
7 = spellHC (Spell HitChance)
8 = spellBuffName (Buff Name)
9 = spellObjName (Object Name)
--]]
local ComboTable = {
	Aatrox = {
		DT = {DAMAGE_PHYSICAL},
		AA = {125,1,1,300,1,1,_R,12},
		q = {800,1800,0.27,100,false,"Circular",2},
		w = {350,0,0,0,false,"Self",1},
		e = {1000,1600,0.27,40,false,"Line",3},
		r = {550,0,0.5,false,"Still",2},
	},
	Ahri = {
		DT = {DAMAGE_MAGICAL},
		AA = {550,1,1}, 
		q = {870,1800,0.250,90,false,"Line",2},
		w = {800,0,0,0,false,"Self",1},
		e = {975, 1600,0.250,80,true,"Line",2},
		r = {600,0,0.2,50,false,"Line",1},
	},
	Akali = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1},
		q = {600,0,0,0,false,"Target",1},
		w = {700,0,0.5,150,false,"Self",1},
		e = {325,0,0.3,0,false,"Still",1},
		r = {800,0,0,0,false,"Target",1},
	},
	Alistar = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1}, 
		q = {365,0,0.3,0,false,"Still",2},
		w = {650,0,0,0,false,"Target",1},
		e = {575,0,0,0,false,"Heal",1},
		r = {120,0,0,0,false,"Self",1},
	},
	Amumu = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1},
		q = {1100,2050,0.25,95,true,"Line",2},
		w = {300,0,0.25,0,false,"Still",1,nil,"Despairpool_tar.troy"},
		e = {350,0,0.3,0,false,"Still",1},
		r = {550,0,0.3,0,false,"Still",2},
	},
	Anivia = {
		DT = {DAMAGE_MAGICAL},
		AA = {600,1,1}, 
		q = {1100,860,0.25,110,false,"Line",2},
		w = {1000,0,0.5,50,false,"Circular",2},
		e = {650,0,0,0,false,"Target",1},
		r = {625,0,0,200,false,"Circular",2,nil,"cryo_storm_green_team.troy"},
	},
	Annie = {
		DT = {DAMAGE_MAGICAL},
		AA = {625,1,1}, 
		q = {625,0,0,0,0,false,"Target",1},
		w = {625,0,0.5,30,false,"Circular",2},
		e = {600,0,0,0,false,"Self",1},
		r = {600,0,0.5,100,false,"Circular",2},
	},
	Ashe = {
		DT = {DAMAGE_PHYSICAL},
		AA = {600,1,1}, 
		q = {0,0,0,0,false,"Self",1},
		w = {600,900,0.5,80,true,"Line",2},
		e = {0,0,0,0,false,nil,1},
		r = {math.huge,1600,0.5,130,false,"Line",3},
	},
	Blitzcrank = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1},
		q = {925,1800,0.5,70,true,"Line",2},
		w = {0,0,0,0,false,"Self",1},
		e = {125,0,0,0,false,"Self",1},
		r = {520,0,0.5,0,false,"Self",2},
	},
	Caitlyn = {
		DT = {DAMAGE_PHYSICAL},
		AA = {650,1,1}, 
		q = {1250,2200,0.25,90,false,"Line",2},
		w = {800,1450,0.5,80,false,"Line",2},
		e = {950,2000,0.7,80,true,"Line",2},
		r = {1500 + (500 * myHero:GetSpellData(_R).level),0,0,0,false,"Target",1},
	},
	Cassiopeia = {
		DT = {DAMAGE_MAGICAL},
		AA = {550,1,1}, 
		q = {850,20,0.5,80,false,"Circular",2},
		w = {850,0,0.5,100,false,"Circular",2},
		e = {700,0,0,0,false,"Target",1},
		r = {815,0,0.75,100,false,"Circular",2},
	},
	Chogath = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1}, 
		q = {950,20,0.9,100,false,"Circular",2},
		w = {300,0,0.25,80,false,"Circular",2},
		e = {0,0,0,0,false,"CastOnce",1},
		r = {150,0,0,0,false,"Target",1},
	},
	Corki = {
		DT = {DAMAGE_PHYSICAL},
		AA = {550,1,1}, 
		q = {825,0,0.5,100,false,"Circular",2},
		w = {800,700,0,100,false,"Line",2},
		e = {600,0,0,0,false,"Self",2},
		r = {1225,825.5,0.5,60,true,"Line",2},
	},
	Darius = {
		DT = {DAMAGE_PHYSICAL},
		AA = {125,1,1}, 
		q = {270,0,0.5,0,false,"Still",2},
		w = {125,0,0,0,false,"Self",1},
		e = {550,1500,0.5,80,false,"Circular",2},
		r = {475,20,0.5,0,false,"Target",1},
	},
	Diana = {
		DT = {DAMAGE_MAGICAL},
		AA = {150,1,1}, 
		q = {900,20,0.5,100,false,"Circular",2},
		w = {400,0,0.5,0,false,"Still",2},
		e = {450,0,0.5,0,false,"Still",2},
		r = {825,0,0,0,false,"Target",2},
	},
	DrMundo = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1},
		q = {900,1500,0.5,75,true,"Line",2},
		w = {325,0,0,0,false,"Still",1},
		e = {0,0,0,0,false,"Self",1},
		r = {0,0,0,0,false,"Self",1},
	},
	Draven = {
		DT = {DAMAGE_PHYSICAL},
		AA = {550,1,1}, 
		q = {550,0,0,0,false,"Self",1},
		w = {550,0,0,0,false,"Self",1},
		e = {1050,1600,0.5,130,false,"Line",2},
		r = {math.huge,2000,1,160,false,"Line",3},
	},
	Elise = {
		DT = {DAMAGE_MAGICAL},
		AA = {550,1,1,125,1,1}, 
		q = {625,0,0,0,false,"Target",1,475,0,0,0,false,"Target",1},
		w = {950,1.2,0.35,100,true,"Line",1,225,0,0,0,false,"Self",1},
		e = {1075,1.4,0.25,70,true,"Line",2,975,20,0,0,false,"Target",1},
		r = {0,0,0,0,false,"Self",1,0,0,0,0,false,"Self",1},
	},
	Evelynn = {
		DT = {DAMAGE_MAGICAL},
		AA = {125,1,1}, 
		q = {500,20,0.5,80,false,"Self",2},
		w = {500,0,0,0,false,"Self",1},
		e = {225,0,0,0,false,"Target",1},
		r = {650,0,0.6,100,false,"Circular",2},
	},
	Ezreal = {
		DT = {DAMAGE_PHYSICAL},
		AA = {550,1,1}, 
		q = {1150,1200,0.5,80,true,"Line",2},
		w = {1000,1200,0.5,80,false,"Line",2},
		e = {475,0,0.5,80,false,"Circular",1},
		r = {math.huge,2000,1,160,false,"Line",3},
	},
	FiddleSticks = {
		DT = {DAMAGE_MAGICAL},
		AA = {480,1,1}, 
		q = {575,0,0,0,false,"Target",1},
		w = {475,0,0,0,false,"Target",1,nil,"Drain.troy"},
		e = {750,0,0,0,false,"Target",1},
		r = {800,0,1.7,400,false,"Circular",2}
	},
	Fiora = {
		DT = {DAMAGE_PHYSICAL},
		AA = {125,1,1},
		q = {600,0,0,0,false,"Target",1},
		w = {125,0,0,0,false,"Self",1},
		e = {125,0,0,0,false,"Self",1},
		r = {400,0,0,0,false,"Target",1},
	},
	Fizz = {
		DT = {DAMAGE_MAGICAL},
		AA = {175,1,1},
		q = {550,0,0,0,false,"Target",1},
		w = {200,0,0,0,false,"Target",1},
		e = {600,0,0.5,80,false,"Circular",2},
		r = {1000,1200,0.5,100,false,"Line",2},
	},
	Galio = {
		DT = {DAMAGE_MAGICAL},
		AA = {125}, 
		q = {},
		w = {},
		e = {},
		r = {},
	},
	Gangplank = {
		DT = {DAMAGE_PHYSICAL},
		AA = {}, 
		q = {},
		w = {},
		e = {},
		r = {},
	},
	Garen = {
		DT = {DAMAGE_PHYSICAL},
		AA = {},
		q = {},
		w = {},
		e = {},
		r = {},
	},
	Gragas = {
		DT = {DAMAGE_MAGICAL},
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Graves = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Hecarim = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Heimerdinger = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Irelia = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Janna = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Jarvan = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Jax = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Jayce = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Jinx = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Karma = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Karthus = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Kassadin = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Katarina = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Kayle = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Kennen = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Khazix = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Kogmaw = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	LeBlanc = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Leesin = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Leona = {
		AA = {125,1,1},
		q = {},
		w = {},
		e = {},
		r = {},
	},
	Lissandra = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Lucian = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Lulu = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Lux = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Malphite = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Malzahar = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Maokai = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	MasterYi = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Missfortune = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Mordekaiser = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Morgana = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Nami = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Nasus = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Nautilus = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Nidalee = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Nocturne = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Nunu = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Olaf = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Orianna = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Pantheon = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Poppy = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Quinn = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Rammus = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Renekton = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Rengar = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Riven = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Rumble = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Ryze = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Sejuani = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Shaco = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Shen = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Shyvana = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Singed = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Sion = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Sivir = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Skarner = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Sona = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Soraka = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Swain = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Syndra = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Talon = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Taric = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Teemo = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Thresh = {
		AA = {550,1,1},
		q = {},
		w = {},
		e = {},
		r = {},
	},
	Tristana = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Trundle = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Tryndamere = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	TwistedFate = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Twitch = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Udyr = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Urgot = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Varus = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Vayne = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Veigar = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Vi = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Viktor = {
		AA = {}, 
		q = {600,0,0,0,false,"Target",1},
		w = {625,0,0.75,100,false,"Circular",2},
		e = {540,1200,0.25,50,false,"Line",2},
		r = {700,0,0.250,100,false,"Circular",2},
	},
	Vladimir = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Volibear = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Warwick = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	MonkeyKing = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Xerath = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	XinZhao = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Yasuo = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Zac = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Zed = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Ziggs = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Zilean = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
	Zyra = {
		AA = {}, q = {},
		w = {},
		e = {},
		r = {},
	},
}
local cd = ComboTable[myHero.charName]
if cd == nil then print("AIOCombo: Denied") return end
function RefreshTable()
	if EmergencyStop == true then return end
	FormCheck()
	for i, combat in pairs(ComboTable) do
		if combat == cd then
			ComboVar = myHero.charName
			if cd.AA[1] == nil then print("AIOCombo: Something isn't right. Please contact Pain with your current Champion's Name and what your were doing when you got this message") EmergencyStop = true end
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
				PrintFloatText(myHero, 1, "Refreshing Data")
			end
			break
		end
	end
end
function Load()
	print("AIOCombo: Loaded")
	CastOnceFS = false
	delayVar = false
	BuffedVar = false
	qObjVar,wObjVar,eObjVar,rObjVar = false,false,false,false
	qBuffVar,wBuffVar,eBuffVar,rBuffVar = false,false,false,false
	delay = GetTickCount()
	delay2 = GetTickCount()
	RefreshTable()
	
	Menu = scriptConfig("AIOCombo: Developer","AIOC")
	
	Menu:addSubMenu("AIOCombo: General", "General")
	Menu.General:addParam("Combo", "Activate Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu.General:addParam("Harass", "Activate Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
	
	Menu:addSubMenu("AIOCombo: Drawing", "Draw")
	Menu.Draw("ReloadingDRAW", "[You will have to reload your script to view changes]", SCRIPT_PARAM_INFO,"")
	if myHero.charName == "Thresh" then
		Menu:addSubMenu("AIOCombo: Personal", "Personal")
		Menu.Draw:addParam("showeUsage", "Show 'How would you like to use E'", SCRIPT_PARAM_ONOFF, true)
			Menu.Personal:addParam("eUsage", "", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("T"))
			Menu.Personal:addParam("eUsageInfo", "How would you like to use E: ", SCRIPT_PARAM_INFO, 0)
		if Menu.Draw.showeUsage == true then	
			Menu.Personal:permaShow("eUsageInfo")
		end
	end
	ts = TargetSelector(TARGET_NEAR_MOUSE, 1750, TSDamageType, false)
	ts.name = myHero.charName
	Menu:addTS(ts)
	FakeLoad = false
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
	
end
function Misc()
	if not CastOnceFS then CastOnce() end
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
	end
	if wObjName ~= nil and obj.name == wObjName then
		wObjVar = true
	end
	if eObjName ~= nil and obj.name == eObjName then
		eObjVar = true
	end
	if rObjName ~= nil and obj.name == rObjName then
		rObjVar = true
	end
end
function OnDeleteObj(obj)
	if qObjName ~= nil and obj.name == qObjName then
		qObjVar = false
	end
	if wObjName ~= nil and obj.name == wObjName then
		wObjVar = false
	end
	if eObjName ~= nil and obj.name == eObjName then
		eObjVar = false
	end
	if rObjName ~= nil and obj.name == rObjName then
		rObjVar = false
	end
end
function Blitzcrank()
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Janna()
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Karma()
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
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Morgana()
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Nami()
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Nidalee()
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Sona()
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Soraka()
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function Thresh()
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
	if Menu.Personal.eUsage == true then
		Menu.Personal.eUsageInfo = "To Pull"
	else
		Menu.Personal.eUsageInfo = "To Push"
	end
end
function Zyra()
	if Menu.General.Combo then
	
	elseif Menu.General.Harass then
	
	end
end
function DefaultCombo()
	
end
--[[
FakYou
--]]