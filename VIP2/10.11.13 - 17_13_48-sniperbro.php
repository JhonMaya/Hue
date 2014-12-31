<?php exit() ?>--by sniperbro 72.24.92.134
---------------------------------- START Encryption ----------------------------------------------------------
---------------------------------- START CHECK ---------------------------------------------------------------
function protect(table) return setmetatable({}, { __index = table, __newindex = function(table, key, value) end, __metatable = false }) end
function rawset2(table, value, id) end
--overload check (CastSpell GetUser GetLoLPath addresses should be almost equal)
local a1 = tonumber(string.sub(tostring(_G.CastSpell), 11), 16)
local a2 = tonumber(string.sub(tostring(_G.GetUser), 11), 16)
local a3 = tonumber(string.sub(tostring(_G.GetLoLPath), 11), 16)
if math.abs(a2-a1) > 75000 and math.abs(a3-a2) > 75000 then print("Cass Whooping: Unauthorized User") return end

namez = protect {
	["vadash"] = true, ["dekaron2"] = true, ["weeeqt"] = true, ["sniperbro"] = true, ["149kg"] = true, ["sida"] = true, ["risurigami"] = true, 
	["sabaku"] = true, ["kriksi"] = true, ["dienofail"] = true, ["maestro29"] = true, ["aking92"] = true, ["hahahax"] = true, 
	["urbanhazard"] = true, ["semtize"] = true, ["orgamarius"] = true, ["noobarrow"] = true, ["prannkii"] = true, ["olorin"] = true, ["klokje"] = true, 
	["smurfnoobhaha"] = true, ["haz894"] = true, ["gastan"] = true, ["maxemz"] = true, ["agn11059555"] = true, ["divinemethod"] = true,
	["lnteractive"] = true, ["ongie119"] = true, ["marlow1337"] = true, ["codex1337"] = true, ["rxemi"] = true, ["pappsen"] = true, 
	["web38"] = true, ["dyingman101"] = true, ["devide"] = true, ["rompeansikt"] = true, ["billgoiaba"] = true, ["xpersona"] = true, 
	["bnbhvsh"] = true, ["omnipot3nt"] = true, ["g0rning"] = true, ["gulptryne"] = true, ["flavor2443"] = true, ["codeon"] = true, 
	["watercooled"] = true, ["methodxb"] = true, ["l4a"] = true, ["tazbox"] = true, ["0815hack"] = true, ["bhoffman"] = true, ["xuzhe56828608"] = true,
	["duzzk"] = true, ["eevi"] = true, ["nizadar"] = true, ["maniaclucas"] = true, ["kadi"] = true, ["sub7kiddies"] = true, ["traktor"] = true,
	["frylockxxx"] = true, ["sinobis"] = true, ["ejdernefesi"] = true, ["mullerlight"] = true, ["hardcider"] = true, ["mewkyy"] = true, ["austyn"] = true,
	["troni1278"] = true, ["walking hell"] = true, ["vortur"] = true, ["hans_meier"] = true, ["lienniar"] = true, ["shivan"] = true, ["robban391"] = true,
	["dane517"] = true, ["stoon"] = true, ["lezbro"] = true, ["xeph"] = true, ["yeezus"] = true, ["lastchicken"] = true, ["xxgowxx"] = true,
	["zikkah"] = true, ["akasai5"] = true, ["nixxor"] = true, ["oxeem"] = true, ["empty1991"] = true, ["nizahe"] = true,
	["antihero"] = true, ["hellfish117"] = true, ["serpicos"] = true, ["feez"] = true, ["zero"] = true, ["rep09"] = true, 
	["ijuno"] = true, ["morza"] = true, ["cryo"] = true, ["poindexter"] = true, ["toxicteddy"] = true,
	["sidesteal"] = true, ["rftiiv15"] = true, ["whatever525"] = true, ["grapesodur"] = true, ["wildflower1"] = true, ["lolsquib"] = true,
	["menime"] = true, ["natethegreat"] = true, ["blackhype"] = true, ["lucas22490"] = true, 
	["listerkeler"] = true, ["q179339065"] = true, ["joel1975"] = true, ["hornax"] = true, ["shaunyboi"] = true, ["nudex"] = true,
	["griz"] = true, ["afh100"] = true, ["mrsynix"] = true, ["el mamuth"] = true, ["stormy21"] = true, ["urged"] = true,
	["jta87k"] = true, ["endinglegacy"] = true, ["eddow"] = true, ["xpain"] = true,
	["recoba20"] = true, ["pixieduster"] = true, ["visionsz"] = true, ["gianmaranon"] = true, ["frosttfire"] = true, ["plobrother"] = true, 
	["liquidace"] = true, ["chewbaca"] = true, ["deeka"] = true, ["manolo"] = true, ["kostaman"] = true, ["tostii"] = true,
	["roxterrocking"] = true, ["minimoney1"] = true, ["ilikebacon"] = true, ["banned4haxx"] = true, ["quickdraw"] = true,
	["andysmalll"] = true, ["jakdo"] = true, ["paradoxel"] = true, ["sillyfang"] = false, ["lumi"] = true, ["h4ck"] = true, ["jcannon"] = true,
	["pyrophenix"] = true, ["lepaap"] = true, ["eunn"] = true, ["enzyme"] = true, ["godly50"] = true, ["clamity"] = true, ["someguy"] = true,
	["quixor329"] = true, ["donnerschlag"] = true, ["omfgabriel"] = true, ["robtomo"] = true, ["sillyspaz"] = true, ["cinderlol"] = true, ["shinwoojin"] = true,
	["botting06scape"] = true, ["babs"] = true, ["ragequit"] = true, ["maddog00700"] = true, ["smetsson"] = true, ["anekraf"] = true, ["zhefish"] = true,
	["hybrin"] = true, ["jst94"] = true, ["draesia"] = true, ["augustusecnarf"] = true, ["420yoloswag"] = true, ["timmy16744"] = true, ["3rasus"] = true,
	["ailikes"] = true, ["wukeokok"] = true, ["osaka"] = true, ["badger31428"] = true, ["hoempa"] = true, ["skribbs"] = true, ["anik"] = true, ["epoc"] = true,
	["haxn23"] = true, ["funcof"] = true, ["xnoregretz2u"] = true, ["revolltz"] = true, ["frozer"] = true, ["drak"] = true, ["ghostshank"] = true, 
	["lazer713"] = true, ["tempaccount"] = true, ["lullaby708"] = true,  ["iuser99"] = true, ["bobbobbob"] = true,  ["brez"] = true,  ["yrmom3141"] = true, 
	["getsnipeddown"] = true, ["oallan"] = true, ["tandu"] = true, ["chancity"] = true, ["kafetao"] = true, ["ojay"] = true, ["mptrash"] = true,
	["sezan91"] = true, ["batcan0704"] = true, ["bishopx4200"] = true, ["gilberto_san"] = true, ["lukout"] = true, ["ozzeh"] = true,
	["johndoe"] = true, ["nimus14"] = true, ["ahkasha"] = true, ["chreyz"] = true, ["dottie"] = true, ["97576743"] = true, ["renews"] = true, ["nestle"] = true, 
	["xrated"] = true, ["birdpoodan"] = true, ["lhr"] = true, ["jakesmurf"] = true, ["diwas89"] = true, ["kain"] = true, ["andyi"] = true, 
	["anubis342"] = true, ["grimmerz"] = true, ["badstrip"] = true, ["magmia"] = true, ["dosentmatter123"] = true, ["polycarbonite"] = true,
	["tacd"] = true, ["nevalopo"] = true, ["chriss"] = true, ["sweetdreams"] = true, ["nirvana1221"] = true, ["echomango"] = true, ["slashxcdoe"] = true,
	["jujupie"] = true, ["abortion"] = true, ["kainv2"] = true, ["barasia283"] = true, ["chipper308"] = true, ["kelwynn"] = true, ["gatugeniet"] = true,
	["silent84"] = true,  ["seamlessly"] = true, ["thunderbow9"] = true, ["sunnr"] = true, ["darkraiser"] = true, ["patton319"] = true, 
	["bookuu"] = true,  ["erwinbeck"] = true, ["alannismason"] = true, ["dadeus"] = true,  ["luyk3n"] = true, ["moralityboy"] = true, ["hesobig702"] = true,
	["proklaus"] = true, ["salnwa"] = true, ["phexon"] = true, ["golgari01"] = true, ["floresrikko"] = true, ["zudren"] = true, ["k9thebeast"] = true, ["mkwarrior"] = true,
	["crackle"] = true, ["mohky"] = true, ["teino"] = true, ["consfearacy"] = true, ["collster37"] = true, ["bewild31"] = true, ["hulk0"] = true, 
	["heelx"] = true, ["pwnyhofpl0x"] = true, ["landliebe"] = true, ["ares"] = true, ["alanhoff"] = true, ["passionford"] = true, ["ravenmage"] = true, 
	["alorzy91"] = true, ["igotcslol"] = true, ["pezlar"] = true, ["verajicus"] = true, ["xiiiii"] = true, ["wtf2020"] = true, ["almightythor101"] = true,
	["raingul"] = true, ["fuli88"] = true, ["emirc"] = true, ["scarem"] = true, ["jewish"] = true, ["merark"] = true, ["ttoast"] = true,
	["euronymous2"] = true, ["confusingart"] = true, ["surrealpower"] = true, ["zestia"] = true, ["ires"] = true,  ["sonat"] = true, ["kingkidd"] = true,
	["markusc"] = true, ["rockstiff"] = true, ["denzohd"] = true, ["bankreis"] = true, ["maxghall"] = true,  ["lexxes"] = true, ["redarmy"] = true,
	["season"] = true, ["systemc"] = true, ["bobbyjayblack"] = true, ["johay"] = true, ["frost21"] = true,  ["amannda"] = true, ["andreksu"] = true,
	["juniorez"] = true, ["silverisg"] = true, ["pyrolinchen"] = true, ["bstokell"] = true, ["dryice"] = true,  ["xll.de"] = true, ["lukeyboy89"] = true,
	["yoshara"] = true, ["exile"] = true, ["benyuk"] = true, ["myroomun"] = true, ["phebos"] = true,  ["trueprd"] = true, ["setsna"] = true,
	["onc86"] = true, ["enigmabotr"] = true, ["nebuer"] = true, ["swtouiguihunibun"] = true, ["ikaeren"] = true,  ["wgmiskel"] = true, ["syyke"] = true,
	["alveron"] = true, ["lordorion420"] = true, ["sloped"] = true, ["phbn93"] = true, ["barad3eey"] = true, ["switchy"] = true,
	["yurippe245126"] = true, ["straf"] = true, ["aquarianpython"] = true, ["tortelles"] = true, ["hyper689"] = true, ["waffle"] = true,
	["subm3ntor"] = true, ["blacklycon"] = true, ["lolhi"] = true, ["brookss"] = true, ["jahwe"] = true, ["kazie"] = true, ["remus3"] = true,
	["night3"] = true, ["blm95"] = true, ["davids"] = true, ["boboben1"] = true, ["nekomimibadik"] = true, ["shanye"] = true, ["chrisair"] = true, 
	["configz"] = true, ["samm3"] = true, ["ryzebol"] = true, ["solofire"] = true, ["bigbudda87"] = true, ["cuongvu00"] = true, ["armagedonas"] = true,
	["everrich"] = true, ["herpnderp"] = true, ["swain"] = true, ["banditx1"] = true, ["mus"] = true, ["johnt717"] = true, ["xffffa"] = true,
	["riskinbrisk"] = true, ["hatemost"] = true, ["zzyzxer"] = true, ["khuong"] = true, ["jaximus123"] = true, ["thegr81"] = true,
	["jazzyjazz77"] = true, ["taistelu"] = true, ["bozarking"] = true,  ["titan"] = true, ["heist"] = true,  ["zsoka"] = true,
	["derpherp"] = true, ["likagangsta"] = true, ["tmsjah"] = true, ["rafaelinux"] = true, ["fatfurball"] = true, ["bercley"] = true,
	["iodas1"] = true, ["fragmot"] = true, ["imseriouslyoscar"] = true, ["bensom6"] = true, ["cleanthugg"] = true, ["monster255"] = true,
	["billypaiva"] = true, ["damnrats"] = true, ["ssgleader"] = true, ["ghostrider9310"] = true, ["kcire"] = true, ["dikki"] = true,
	["synchrodoom"] = true, ["fueledbyrainbows"] = true, ["alxdjo"] = true, ["ezgamer"] = true, ["selfadmirer"] = true, ["aristu"] = true,
	["dmassa"] = true, ["favas22"] = true, ["cabana"] = true, ["totos2"] = true, ["jahbong"] = true, ["koerdum"] = true,
	["turtlebot"] = true, ["toxic 123"] = true, ["nathanha20"] = true, ["vixemainha"] = true, ["rewind"] = true, ["keoshin"] = true, 
	["ryanator"] = true, ["thehasuman"] = true, ["veers13"] = true, ["tragboo"] = true, ["valo101"] = true, ["xdjeffxd"] = true,
	["travisty"] = true, ["cromer"] = true, ["hackedhacker"] = true, ["wrenkla"] = true, ["xero666"] = true, ["rtstrauma"] = true,
	["tipanaya"] = true, ["hied"] = true, ["gambit1994"] = true, ["a822022"] = true, ["chrisokgo"] = true, ["pyryoer"] = true,
	["kaic420"] = true, ["windowsmediaman"] = true, ["legitmerk"] = true, ["megakillatron"] = true, ["theblackrat"] = true, 
	["raceu2hell"] = true, ["ragekid"] = true, ["exit"] = true, ["errinqq"] = true,  ["kihan112"] = true, ["sash21234"] = true,
	["heelxpc"] = true, ["spudgy"] = true, ["ottohr"] = true, ["frinshy"] = true, ["st4ck3r"] = true, ["b4ckst4b"] = true,
	["fifawinner"] = true, ["itsjonis"] = true, ["swerve"] = true, ["jarnexs"] = true, ["faye reagan"] = true, ["knaegge"] = true,
	["bibow06"] = true, ["xdedde"] = true, ["twk2nd"] = true, ["blauvk13"] = true, ["teargas111"] = true, ["pqmailer"] = true, 
	["fabioc"] = true, ["ms el jefe"] = true, ["pkz"] = true, ["nightmare"] = true, ["cbkixo"] = true, ["unknown88"] = true, 
	["peppoonline"] = true, ["legotya"] = true, ["meuovo"] = true, ["shiplx"] = true, ["kerath"] = true, ["xxbrteamxx"] = true, 
}    
_G.rawset = rawset2
if namez[_G.GetUser():lower()] == nil or namez[_G.GetUser():lower()] == false then print("Cass Whooping: Unauthorized User") return end
---------------------------------- END CHECK --------------------------------------------------------------------

if myHero.charName ~= "Cassiopeia" then return end
local priorityTable = {
	AP = {
		"Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
		"Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
		"Rumble", "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra",
	},
	Support = {
		"Blitzcrank", "Janna", "Karma", "Lulu", "Nami", "Sona", "Soraka", "Thresh", "Zilean",
	},
     
	Tank = {
		"Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Shen", "Singed", "Skarner", "Volibear",
		"Warwick", "Yorick", "Zac", "Nunu", "Taric", "Alistar", "Leona",
	},
     
	AD_Carry = {
		"Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "Jinx", "KogMaw", "Lucian", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
		"Talon", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Zed", "MasterYi",
	},
     
	Bruiser = {
		"Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nautilus", "Nocturne", "Olaf", "Poppy",
		"Renekton", "Rengar", "Riven", "Shyvana", "Trundle", "Tryndamere", "Udyr", "Vi", "MonkeyKing", "XinZhao", "Aatrox"
	},
}

function OnLoad()
	LoadTargets()
	LoadVariables()
	LoadMenu()	
	LoadSkillRanges()
	LoadVIPPrediction()
	LoadMinions()
	LoadSummonerSpells()
	LoadEnemies()
	if ignite ~= nil then
	    LoadIgniteMenu()
	end
end
function OnUnload()
	PrintFloatText(myHero,2,"Cass Whooping Release v2.222 UnLoaded!")
end
function LoadIgniteMenu()
	Config:addSubMenu("Ignite Settings", "igniteSettings")
	Config.igniteSettings:addParam("KSMode", "KS Mode", SCRIPT_PARAM_ONOFF, true)
	Config.igniteSettings:addParam("ComboMode", "Combo Mode", SCRIPT_PARAM_ONOFF, false)
	
	Config.ShowInGame:addSubMenu("Show Ignite","ShowIgnite")
	Config.ShowInGame.ShowIgnite:addParam("KSMode", "Show KS Mode", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowIgnite:addParam("ComboMode", "Show Combo Mode", SCRIPT_PARAM_ONOFF, false)
end	
function LoadMenu()
	Config = scriptConfig("Cass Whooping Milestone", "Cass Whooping Milestone")
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 925, DAMAGE_MAGIC, true)
	ts.name = "Cassiopeia"
	Config:addSubMenu("Target selector", "tsSettings")
	Config.tsSettings:addTS(ts)

	Config:addParam("Cassi","Cassiopeia", SCRIPT_PARAM_INFO, " ")
	Config:permaShow("Cassi")
	
	Config:addSubMenu("Combat Keys", "combatKeys")
	
	Config.combatKeys:addSubMenu("Harass Settings", "hSettings")
	Config.combatKeys.hSettings:addParam("harass", "Harass ", SCRIPT_PARAM_ONKEYDOWN, false, 84)
	Config.combatKeys.hSettings:addParam("harassToggle", "Harass Toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, 88)
	Config.combatKeys.hSettings:addParam("harassE", "Use E in harass", SCRIPT_PARAM_ONOFF, false)
	Config.combatKeys.hSettings:addParam("moveToMouseHarass", "Move To Mouse (harass)", SCRIPT_PARAM_ONOFF, false)

	Config.combatKeys:addSubMenu("TeamFight Settings", "tSettings")
	Config.combatKeys.tSettings:addParam("teamFight", "TeamFight (SpaceBar)", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config.combatKeys.tSettings:addParam("KSE", "KS with E", SCRIPT_PARAM_ONOFF, true)
	Config.combatKeys.tSettings:addParam("autoE", "Auto E Poisoned Targets", SCRIPT_PARAM_ONOFF, false)

	Config.combatKeys:addParam("hackE", "Use E directional hack", SCRIPT_PARAM_ONOFF, true)
	
	Config:addSubMenu("Farm Settings", "farmSettings")
	Config.farmSettings:addSubMenu("Lane Farm", "laneFarm")
	Config.farmSettings.laneFarm:addParam("farm", "Farm", SCRIPT_PARAM_ONKEYDOWN, false, 85)
	Config.farmSettings.laneFarm:addParam("farmToggle", "Farm Toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, 90)
	Config.farmSettings.laneFarm:addParam("farmAA", "AA Farm", SCRIPT_PARAM_ONOFF, true)
	Config.farmSettings.laneFarm:addParam("farmQ", "Q Farm", SCRIPT_PARAM_ONOFF, true)
	Config.farmSettings.laneFarm:addParam("farmW", "W Farm", SCRIPT_PARAM_ONOFF, true)
	Config.farmSettings.laneFarm:addParam("farmE", "E Farm", SCRIPT_PARAM_ONOFF, true)
	Config.farmSettings.laneFarm:addParam("farmMove", "Move to Mouse on Farm", SCRIPT_PARAM_ONOFF, false)
	--Config.farmSettings.laneFarm:addParam("minionMarker", "Minion Farm", SCRIPT_PARAM_ONOFF, true)

	Config.farmSettings:addSubMenu("Jungle Farm", "jungleFarm")
	Config.farmSettings.jungleFarm:addParam("jungleFarming", "Jungle Farm", SCRIPT_PARAM_ONKEYDOWN, false, 74)
	Config.farmSettings.jungleFarm:addParam("jungleAA", "AA Farm", SCRIPT_PARAM_ONOFF, true)
	Config.farmSettings.jungleFarm:addParam("jungleFarmQ", "Q Jungle Farm", SCRIPT_PARAM_ONOFF, true)
	Config.farmSettings.jungleFarm:addParam("jungleFarmW", "W Jungle Farm", SCRIPT_PARAM_ONOFF, true)
	Config.farmSettings.jungleFarm:addParam("jungleFarmE", "E Jungle Farm", SCRIPT_PARAM_ONOFF, true)


	Config:addSubMenu("Draw Settings", "drawSettings")
	Config.drawSettings:addParam("DrawCircles", "Draw Circles", SCRIPT_PARAM_ONOFF, false)
	Config.drawSettings:addParam("DrawE", "Draw E Range", SCRIPT_PARAM_ONOFF, false)
	Config.drawSettings:addParam("DrawText", "Draw Text", SCRIPT_PARAM_ONOFF, false)
	Config.drawSettings:addParam("LagFree", "Lag free draw", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Item Settings", "itemSettings")
	Config.itemSettings:addParam("useItems", "Use Items", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Orbwalk Settings", "orbwalkSettings")
	Config.orbwalkSettings:addParam("moveToMouse", "Move To Mouse (combo)", SCRIPT_PARAM_ONOFF, false)
	Config.orbwalkSettings:addParam("OrbWalkAA", "AA While Move to Mouse", SCRIPT_PARAM_ONOFF, false)

	Config:addSubMenu("Prodiction Settings", "prodictionSettings")
	Config.prodictionSettings:addParam("CanNotMissMode", "CanNotMissMode (vip donators)", SCRIPT_PARAM_ONOFF, false)

	Config:addSubMenu("Ult Settings", "ultSettings")	
	Config.ultSettings:addParam("manualR", "Cast Ult", SCRIPT_PARAM_ONKEYTOGGLE, false, 82)
	Config.ultSettings:addParam("smartUlt", "Use Assisted Ult", SCRIPT_PARAM_ONOFF, true)
	Config.ultSettings:addParam("autoUlt", "Use Auto Ult Functions [F5]", SCRIPT_PARAM_ONKEYTOGGLE, true, 116) 
	Config.ultSettings:addParam("useUltKillable", "Use Ult when Killable", SCRIPT_PARAM_ONKEYTOGGLE, true, 86)
	Config.ultSettings:addParam("setUltEnemies", "No. Enemies facing", SCRIPT_PARAM_SLICE, 2, 1, 5, 0)
	Config.ultSettings:addParam("setUltEnemiesInRange", "No. Enemies in Range", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)


	Config:addSubMenu("Passive Settings", "pSettings")
	Config.pSettings:addParam("autoPT", "Auto Passive tracking [F3]", SCRIPT_PARAM_ONKEYTOGGLE, false, 114)
	Config.pSettings:addParam("autoAA", "AA Passive Stack [F4]", SCRIPT_PARAM_ONKEYTOGGLE, true, 115)

	Config:addSubMenu("Show in Game", "ShowInGame")
	
	Config.ShowInGame:addSubMenu("Show Harass","ShowHarass")
	Config.ShowInGame.ShowHarass:addParam("Harass", "Show Harass on Key", SCRIPT_PARAM_ONOFF, true)
	Config.ShowInGame.ShowHarass:addParam("HarassToggle", "Show Harass on Toggle", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowHarass:addParam("HarassE", "Show E in Harass", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowHarass:addParam("HarassMouse", "Show Harass Move to Mouse", SCRIPT_PARAM_ONOFF, false)

	Config.ShowInGame:addSubMenu("Show Combat Keys","ShowCombat")
	Config.ShowInGame.ShowCombat:addParam("SpaceBar", "Show Teamfight", SCRIPT_PARAM_ONOFF, true)
	Config.ShowInGame.ShowCombat:addParam("KSE", "Show KS with E", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowCombat:addParam("AutoE", "Show Auto E", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowCombat:addParam("hackE", "Show E Hack", SCRIPT_PARAM_ONOFF, false)
	
	Config.ShowInGame:addSubMenu("Show Farm","ShowFarm")

	Config.ShowInGame.ShowFarm:addParam("farm", "Show Lane Farm", SCRIPT_PARAM_ONOFF, true)
	Config.ShowInGame.ShowFarm:addParam("farmToggle", "Show Lane Farm Toggle", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowFarm:addParam("farmAA", "Show Lane Farm AA", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowFarm:addParam("farmQ", "Show Lane Farm Q", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowFarm:addParam("farmW", "Show Lane Farm W", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowFarm:addParam("farmE", "Show Lane Farm E", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowFarm:addParam("farmMove", "Show Move to Mouse on Farm", SCRIPT_PARAM_ONOFF, false)
	--Config.ShowInGame.ShowFarm:addParam("farmMin", "Show Lane Farm Minion Marker", SCRIPT_PARAM_ONOFF, false)

	Config.ShowInGame.ShowFarm:addParam("jungleFarm", "Show Jungle Farm", SCRIPT_PARAM_ONOFF, true)
	Config.ShowInGame.ShowFarm:addParam("jungleAA", "Show Jungle Farm 'AA'", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowFarm:addParam("jungleQ", "Show Jungle Farm Q", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowFarm:addParam("jungleW", "Show Jungle Farm W", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowFarm:addParam("JungleE", "Show Jungle Farm E", SCRIPT_PARAM_ONOFF, false)
	--Config.ShowInGame.ShowFarm:addParam("JungleMin", "Show Jungle Farm Minion Marker", SCRIPT_PARAM_ONOFF, false)

	Config.ShowInGame:addSubMenu("Show Draw", "ShowDraw")
	Config.ShowInGame.ShowDraw:addParam("Circles", "Show Circles", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowDraw:addParam("ERange", "Show E Range", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowDraw:addParam("Text", "Show Text", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowDraw:addParam("LagFree", "Show Lag Free", SCRIPT_PARAM_ONOFF, false)

	Config.ShowInGame:addSubMenu("Show Items", "ShowItems")
	Config.ShowInGame.ShowItems:addParam("items","Show Use Items", SCRIPT_PARAM_ONOFF, false)

	Config.ShowInGame:addSubMenu("Show Orbwalking", "ShowOrb")
	Config.ShowInGame.ShowOrb:addParam("orbmouse","Show move to mouse", SCRIPT_PARAM_ONOFF, true)
	Config.ShowInGame.ShowOrb:addParam("orbAA","Show AA in orbwalk", SCRIPT_PARAM_ONOFF, false)

	Config.ShowInGame:addSubMenu("Show CanNotMiss", "ShowCanNotMiss")
	Config.ShowInGame.ShowCanNotMiss:addParam("CanNotMissMode","Show CanNotMissMode", SCRIPT_PARAM_ONOFF, false)

	Config.ShowInGame:addSubMenu("Show Ult Options", "ShowUlt")
	Config.ShowInGame.ShowUlt:addParam("manualR", "Show Cast Ult", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowUlt:addParam("smartUlt", "Show Assisted Ult", SCRIPT_PARAM_ONOFF, false)
	Config.ShowInGame.ShowUlt:addParam("autor", "Show Auto Ult", SCRIPT_PARAM_ONOFF, true)
	Config.ShowInGame.ShowUlt:addParam("UltKill", "Show Ult Killable", SCRIPT_PARAM_ONOFF, true)
	Config.ShowInGame.ShowUlt:addParam("UltEnemiesFacing", "Show Ult Enemies Facing", SCRIPT_PARAM_ONOFF, true)
	Config.ShowInGame.ShowUlt:addParam("UltEnemiesRange", "Show Ult Enemies in Range", SCRIPT_PARAM_ONOFF, false)

	Config.ShowInGame:addSubMenu("Show Passive Options", "ShowPass")
	Config.ShowInGame.ShowPass:addParam("autoPT","Show Passive Stacker", SCRIPT_PARAM_ONOFF, true)
	Config.ShowInGame.ShowPass:addParam("autoAA","Show AA Passive Stack", SCRIPT_PARAM_ONOFF, false)

	PrintFloatText(myHero,2,"Cass Whooping Release v2.222 Loaded!")
	print("<font color='#0092f2'> >> CassWhoopingRelease: </font> <font color='#0092f2'> Running Version "..curVersion.."</font>")
	ShowInGame()
end
function LoadVariables()
	ignite = nil
	enemyHeros = {}
	enemyHerosCount = 0
	NextShot = 0
	aaTime = 0
	minionRange = false
	tick = 0
	igniteTick = 0
	wTick = 0
	ksDamages = {}
	newTarget = nil
	rTick = 0
	passiveTick = 0
	recalling = false
	curVersion = "2.222"
	UpdateChat = {}
	pIgniteKSMode = false
	pIgniteComboMode = false
	pManualUlt = false
	pSmartUlt = false
	allowR = false
end
function LoadSkillRanges()
	rangeQ = 925
	rangeW = 925
	rangeE = 700
	rangeR = 750
	killRange = 925
end
function LoadVIPPrediction()
	wp = ProdictManager.GetInstance()
	tpQ = wp:AddProdictionObject(_Q, rangeQ + 75, 20000, 0.6, nil, myHero, 
        function(target, vec1, castspell) 
        	if not myHero:CanUseSpell(_Q) == READY then return end
            if GetDistance(vec1) <= rangeQ then
                CastSpell(_Q, vec1.x, vec1.z)
                qTick = GetTickCount()
                qTar = target
            elseif GetDistance(vec1) <= rangeQ + 75 then
                local vec2 = Vector(player) + (Vector(vec1) - Vector(player)):normalized() * (rangeQ)
                if vec2 then
                    CastSpell(_Q, vec2.x, vec2.z)
                end
            end			 
		end)
	tpW = wp:AddProdictionObject(_W, rangeW + 75, 20000, 0.375, nil, myHero,
        function(target, vec1, castspell)
        	if not myHero:CanUseSpell(_W) == READY then return end
            if GetDistance(vec1) <= rangeW then
                CastSpell(_W, vec1.x, vec1.z)
            elseif GetDistance(vec1) <= rangeW + 75 then
                local vec2 = Vector(player) + (Vector(vec1) - Vector(player)):normalized() * (rangeW)
                if vec2 then
                    CastSpell(_W, vec2.x, vec2.z)
                end
            end
        end)
	tpR = wp:AddProdictionObject(_R, rangeR, 2000, 0.75, nil, myHero, 
		function(unit, pos, castspell) 
			if GetDistance(pos) < rangeR and myHero:CanUseSpell(_R) == READY and not unit.dead
				and GetDistance(pos) <= rangeR * 3/4 and GetDistance(pos) > rangeR * 1/4 then 
				allowR = true
				CastSpell(_R, pos.x, pos.z)
				allowR = false
			end 
		end)
	tpR1 = TargetPredictionVIP(rangeR, math.huge, 0.75)

	if Config.prodictionSettings.CanNotMissMode then
	    for i = 1, heroManager.iCount do
	        local hero = heroManager:GetHero(i)
	        if hero.team ~= myHero.team then
	            tpQ:CanNotMissMode(true, hero)
				tpW:CanNotMissMode(true, hero)
	        end
	    end
	end
end
function LoadMinions()
	enemyMinion = minionManager(MINION_ENEMY, rangeQ, player, MINION_SORT_HEALTH_ASC)
	jungleMinion = minionManager(MINION_JUNGLE, rangeQ, player, MINION_SORT_MAXHEALTH_DEC)
end
function LoadSummonerSpells()
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then 
		ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignite = SUMMONER_2
	else 
		ignite = nil
  	end
end
function LoadEnemies()
	for i = 1, heroManager.iCount do
		local hero = heroManager:GetHero(i)
		if hero.team ~= player.team then
			local enemyCount = enemyHerosCount + 1
			enemyHeros[enemyCount] = {object = hero, waittxt = 0, killable = 0 }
			enemyHerosCount = enemyCount
		end
	end
end
function SetPriority(table, hero, priority)
	for i=1, #table, 1 do
		if hero.charName:find(table[i]) ~= nil then
			TS_SetHeroPriority(priority, hero.charName)
		end
	end
end     
function arrangePrioritys(enemies)
	local priorityOrder = {
		[2] = {1,1,2,2,2},
		[3] = {1,1,2,3,3},
		[4] = {1,2,3,4,4},
		[5] = {1,2,3,4,5},
	}
	for i, enemy in ipairs(GetEnemyHeroes()) do
		SetPriority(priorityTable.AD_Carry, enemy, priorityOrder[enemies][1])
		SetPriority(priorityTable.AP,       enemy, priorityOrder[enemies][2])
		SetPriority(priorityTable.Support,  enemy, priorityOrder[enemies][3])
		SetPriority(priorityTable.Bruiser,  enemy, priorityOrder[enemies][4])
		SetPriority(priorityTable.Tank,     enemy, priorityOrder[enemies][5])
	end
end
function LoadTargets()
	if #GetEnemyHeroes() <= 1 then
		PrintChat("Not enough enemies, can't arrange priority's!")
	else
		TargetSelector(TARGET_LESS_CAST_PRIORITY, 0) -- Create a dummy target selector
		arrangePrioritys(#GetEnemyHeroes())
		PrintChat(" >> Arranged priority's!")
	end
end
function OnTick()
	ts:update()
	if hasUpdated then 
		if FileExist(VERSION_PATH) then
			AutoUpdate() 
		end 
	end
	if ignite ~= nil then
		if pIgniteKSMode then
			if Config.igniteSettings.ComboMode then
				pIgniteKSMode = false
				Config.igniteSettings.KSMode = false
			end
		elseif pIgniteComboMode then
			if Config.igniteSettings.KSMode then
				pIgniteComboMode = false
				Config.igniteSettings.ComboMode = false
			end
		elseif Config.igniteSettings.KSMode then
			pIgniteKSMode = Config.igniteSettings.KSMode
			pIgniteComboMode = false
		elseif Config.igniteSettings.ComboMode then
			pIgniteKSMode = false
			pIgniteComboMode = Config.igniteSettings.ComboMode
		end
	end
	if not Config.ultSettings.smartUlt then
		allowR = true
		if Config.ultSettings.manualR and RREADY then
			if GetTickCount()>rTick then
				Config.ultSettings.manualR = false
			end
		else
			Config.ultSettings.manualR = false
		end
	else
		allowR = false
		if Config.ultSettings.manualR and RREADY then
			assistedUltCheck()
		end
	end
	if player:CanUseSpell(_R) == READY then -- vadash's R
		if CountEnemyHeroInRange(killRange) >= Config.ultSettings.setUltEnemies and Config.ultSettings.autoUlt then
			local vec = GetCassMECS(75, rangeR, Config.ultSettings.setUltEnemies, false) -- 80 degree R
			if vec ~= nil and GetDistance(vec) < rangeR then
				allowR = true
				CastSpell(_R, vec.x, vec.z)
				allowR = false
			end
		end
	end	
	if not myHero.dead and (not Config.ultSettings.manualR or myHero:CanUseSpell(_R) ~= READY or Config.ultSettings.smartUlt) then
		rTick = GetTickCount()+5000
		QREADY = (myHero:CanUseSpell(_Q) == READY)
		WREADY = (myHero:CanUseSpell(_W) == READY)
		EREADY = (myHero:CanUseSpell(_E) == READY)
		RREADY = (myHero:CanUseSpell(_R) == READY)
		IREADY = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
		checkKillRange()
		execute()
		orbWalk()
		if Config.pSettings.autoAA and not Config.combatKeys.tSettings.teamFight then
			aaStack()
		end
		if Config.farmSettings.laneFarm.farm or Config.farmSettings.laneFarm.farmToggle and not Config.combatKeys.tSettings.teamFight then
			farmKey()
		end
		if Config.farmSettings.jungleFarm.jungleFarming and not Config.combatKeys.tSettings.teamFight then
			jungleFarm()
		end
		if Config.combatKeys.hSettings.harassToggle or Config.combatKeys.hSettings.harass then
			harassKey()
		end
		if Config.pSettings.autoPT and not Config.combatKeys.tSettings.teamFight and not Config.combatKeys.hSettings.harass then 
			PassiveTracking() 
		end	
	end
end
function assistedUltCheck()
	for i = 1, enemyHerosCount do
		local Enemy = enemyHeros[i].object 
		if ValidTarget(Enemy) and GetDistance(Enemy)<=rangeR then
			local spellPos, enemyCount = GetAoESpellPosition(750, Enemy, 0.75)
			if spellPos and GetDistance(spellPos)<=rangeR then
				allowR = true
				CastSpell(_R, spellPos.x, spellPos.z)
				allowR = false
			end
		end
	end
	Config.ultSettings.manualR = false
end
function OnSendPacket(p)
	local packet = Packet(p)
	if packet:get('name') == 'S_CAST' then
		if packet:get('spellId') == _R then
			if not allowR then
				packet:block()
			end
		end
	end
end
function aaStack()
	if abd and objManager:GetObjectByNetworkId(abd) then
		abc = objManager:GetObjectByNetworkId(abd)
		abd = nil 
	end
	if(abc and abc.valid and attackend and attackend.valid and (GetDistance(attackend,abc)<GetLatency()*1.8 and attackend.health<myHero.totalDamage)) then
		CastPacket(_E,attackend.networkID)
		abc = nil
		attackend = nil  
	end
end
function checkKillRange()
	if WREADY then
		killRange = rangeW
	elseif QREADY then
		killRange = rangeQ
	elseif RREADY  then
		killRange = rangeR
	elseif EREADY then
		killRange = rangeE
	else
		killRange = myHero.range + GetDistance(myHero.minBBox)
	end
end
function Target()
	local currentTarget = nil
	local killMana = 0
	local facing = 0
	local targetSelected = ts.target 
	for i = 1, enemyHerosCount do
		local Enemy = enemyHeros[i].object
		if ValidTarget(Enemy) then
			local pdmg = getDmg("P", Enemy, myHero, 3)
			local qdmg = getDmg("Q", Enemy, myHero, 3)
			local wdmg = getDmg("W", Enemy, myHero, 3)
			local edmg = getDmg("E", Enemy, myHero, 3)
			local rdmg = getDmg("R", Enemy, myHero, 3)
			local ADdmg = getDmg("AD", Enemy, myHero, 3)
			local dfgdamage = (GetInventoryItemIsCastable(3128) and getDmg("DFG",Enemy,myHero) or 0) -- Deathfire Grasp
			local hxgdamage = (GetInventoryItemIsCastable(3146) and getDmg("HXG",Enemy,myHero) or 0) -- Hextech Gunblade
			local bwcdamage = (GetInventoryItemIsCastable(3144) and getDmg("BWC",Enemy,myHero) or 0) -- Bilgewater Cutlass
			local botrkdamage = (GetInventoryItemIsCastable(3153) and getDmg("RUINEDKING", Enemy, myHero) or 0) --Blade of the Ruined King
			local onhitdmg = (GetInventoryHaveItem(3057) and getDmg("SHEEN",Enemy,myHero) or 0) + (GetInventoryHaveItem(3078) and getDmg("TRINITY",Enemy,myHero) or 0) + (GetInventoryHaveItem(3100) and getDmg("LICHBANE",Enemy,myHero) or 0) + (GetInventoryHaveItem(3025) and getDmg("ICEBORN",Enemy,myHero) or 0) + (GetInventoryHaveItem(3087) and getDmg("STATIKK",Enemy,myHero) or 0) + (GetInventoryHaveItem(3209) and getDmg("SPIRITLIZARD",Enemy,myHero) or 0)
			local onspelldamage = (GetInventoryHaveItem(3151) and getDmg("LIANDRYS",Enemy,myHero) or 0) + (GetInventoryHaveItem(3188) and getDmg("BLACKFIRE",Enemy,myHero) or 0)
			local sunfiredamage = (GetInventoryHaveItem(3068) and getDmg("SUNFIRE",Enemy,myHero) or 0)
			local comboKiller = pdmg + qdmg + wdmg + edmg + rdmg + onhitdmg + onspelldamage + sunfiredamage + hxgdamage + bwcdamage + botrkdamage
			local killHim = pdmg + onhitdmg + onspelldamage + sunfiredamage + hxgdamage + bwcdamage + botrkdamage
			if IREADY then
				local idmg = getDmg("IGNITE",Enemy,myHero, 3)
				comboKiller = comboKiller + idmg
				killHim = killHim + idmg
				if GetDistance(Enemy)< 600 then
					if idmg+qdmg+wdmg+edmg*3>=Enemy.health and Config.igniteSettings.ComboMode then
						CastSpell(ignite, Enemy)
					elseif idmg>=Enemy.health and Config.igniteSettings.KSMode then
						CastSpell(ignite, Enemy)
					end
				end
			end
			if QREADY then	
				killMana = killMana + myHero:GetSpellData(_Q).mana
				if GetDistance(Enemy)<=rangeQ then
					killHim = killHim + qdmg
					if qdmg >=Enemy.health and not IsIgnited() then
						table.insert(ksDamages, qdmg)
					end
				end
			end
			if WREADY then
				killMana = killMana + myHero:GetSpellData(_W).mana	
				if GetDistance(Enemy)<=rangeW then
					killHim = killHim + wdmg
					if wdmg >=Enemy.health and not IsIgnited() then
						table.insert(ksDamages, wdmg)
					end
				end
			end
			if EREADY then
				killMana = killMana + myHero:GetSpellData(_E).mana
				if GetDistance(Enemy)<=rangeE then
					killHim = killHim + edmg
					if edmg>=Enemy.health and not IsIgnited() then
						table.insert(ksDamages, edmg)
					end
				end
			end
			if RREADY then
				killMana = killMana + myHero:GetSpellData(_R).mana
				if GetDistance(Enemy)<=rangeR then
					killHim = killHim + rdmg
					if rdmg>=Enemy.health and not IsIgnited()and Config.ultSettings.useUltKillable and Config.ultSettings.autoUlt then
						table.insert(ksDamages, rdmg)
					end
				end
			end
			if RREADY and Config.ultSettings.useUltKillable and Config.ultSettings.autoUlt and (enemyHeros[i].killable == 2 or enemyHeros[i].killable == 3) and not TargetHaveBuff("UndyingRage", Enemy) and not TargetHaveBuff("willrevive", Enemy) and not TargetHaveBuff("JudicatorIntervention", Enemy) and not TargetHaveBuff("ChronoShift", Enemy) and not TargetHaveBuff("rebirthready", Enemy) and not TargetHaveBuff("NocturneShroudofDarkness", Enemy) and not TargetHaveBuff("zacrebirthready", Enemy) and not TargetHaveBuff("aatroxpassiveready", Enemy) then
				if GetDistance(Enemy)<=rangeR and CountEnemyHeroInRange(rangeR+100)==1 then
					CastR(Enemy)
				end
			end
			if next(ksDamages)~=nil then
				table.sort(ksDamages, function (a, b) return a<b end)
				local lowestKSDmg = ksDamages[1]
				if qdmg == lowestKSDmg then
					CastQ(Enemy)
				elseif wdmg == lowestKSDmg then
					CastW(Enemy)
				elseif edmg == lowestKSDmg and Config.combatKeys.tSettings.KSE then
					CastSpellT(_E, Enemy)
				elseif edmg == lowestKSDmg and not Config.combatKeys.tSettings.KSE then
					CastE(Enemy)
				end
				table.clear(ksDamages)
			end
			if GetInventoryItemIsCastable(3128) then  -- DFG      
				comboKiller = comboKiller + dfgdamage + (comboKiller*0.2)
				killHim = killHim + dfgdamage + (killHim*0.2) 
				if GetInventoryItemIsCastable(3146) then -- Hxg
					comboKiller = comboKiller + (hxgdamage*0.2)
					killHim = killHim + (hxgdamage*0.2)
				end
				if GetInventoryItemIsCastable(3144) then -- bwc
					comboKiller = comboKiller + (bwcdamage*0.2)
					killHim = killHim + (bwcdamage*0.2)
				end
				if GetInventoryItemIsCastable(3153) then -- botrk
					comboKiller = comboKiller + (botrkdamage*0.2)
					killHim = killHim + (botrkdamage*0.2)
				end
			end
			currentTarget = Enemy
			if killHim >= currentTarget.health and killMana<= myHero.mana then
				enemyHeros[i].killable = 3
				if GetDistance(currentTarget) <= killRange and not targetSelected then
					if newTarget == nil then
						newTarget = currentTarget
					elseif newTarget.health > killHim then
						newTarget = currentTarget
					else
						local currentTargetDmg = currentTarget.health - killHim
						local newTargetDmg = newTarget.health - killHim
						if currentTargetDmg < newTargetDmg then
							newTarget = currentTarget
						end
					end
				end
			elseif comboKiller >= currentTarget.health then
				enemyHeros[i].killable = 2
				if GetDistance(currentTarget) <= killRange and not targetSelected then
					if newTarget == nil then
						newTarget = currentTarget
					elseif newTarget.health > comboKiller then
						newTarget = currentTarget
					else
						local currentTargetDmg = currentTarget.health - comboKiller
						local newTargetDmg = newTarget.health - comboKiller
						if currentTargetDmg < newTargetDmg then
							newTarget = currentTarget
						end
					end
				end
			else
				enemyHeros[i].killable = 1
				if GetDistance(currentTarget) <= killRange and not targetSelected then
					if newTarget == nil then
						newTarget = currentTarget
					elseif newTarget.health > comboKiller then
						local currentTargetDmg = currentTarget.health - comboKiller
						local newTargetDmg = newTarget.health - comboKiller
						if currentTargetDmg < newTargetDmg then
							newTarget = currentTarget
						end
					end
				end	
			end
			--[[local rCount = CountEnemyHeroInRange(killRange)
			if rCount >= 1 then --dek's R
				if GetDistance(Enemy)<=killRange and not targetSelected then
					RPos = tpR:GetPrediction(Enemy)
					if RPos then
						if GetDistance(RPos)<GetDistance(Enemy) then
							facing = facing + 1
							if facing>= Config.setUltEnemies and Config.autoUlt then
								CastSpell(_R, RPos.x, RPos.z)
							end
						end
						
					end
				end		
			end]]
		else
			killable = 0
		end
	end -- end of for loop
	 if ValidTarget(newTarget) then			
        if GetDistance(newTarget)>killRange then			
        	newTarget = nil			
		end			
		CastSkills(newTarget)			
	else			
		newTarget = nil			
	end
	if ValidTarget(targetSelected) then
		newTarget = targetSelected
		for i = 1, enemyHerosCount do
		local Enemy = enemyHeros[i].object
			if ValidTarget(Enemy) then 
				if newTarget ~= Enemy then
					local edamage = getDmg("E", newTarget, myHero, 3)
					if Config.combatKeys.tSettings.autoE then 
						if edamage/3<newTarget.health then
							CastE(newTarget)
						end
					else
						if Config.combatKeys.tSettings.teamFight then
							if edamage/3<newTarget.health then
								CastE(newTarget)
							end
						end
					end
				else
					if Config.combatKeys.tSettings.autoE then 
						CastE(newTarget)
					else
						if Config.combatKeys.tSettings.teamFight then
							CastE(newTarget)
						end
					end
				end
			end
		end
		if Config.combatKeys.tSettings.teamFight then
			CastItems(newTarget, true)
			CastQ(newTarget)
			CastW(newTarget)
		end
	end
end
function OnGainBuff (unit, buff)
	if unit.isMe and unit.valid then
		if buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinRecallImproved" then
			recalling = true
		end
	end
end
function OnLoseBuff (unit, buff)
	if unit.isMe and unit.valid then
		if buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinRecallImproved" then
			recalling = false
		end
	end
end
function execute()
	Target()
end
function IsIgnited(target)
	if TargetHaveBuff("SummonerDot", target) then
		igniteTick = GetTickCount()
		return true
	elseif igniteTick == nil or GetTickCount()-igniteTick>500 then
		return false
	end
end
function farmKey()
	enemyMinion:update()
	if not recalling then
		for i, minion in pairs(enemyMinion.objects) do
			if minion.valid then
				local range = myHero.range + GetDistance(myHero.minBBox)
				local qdamage = getDmg("Q", minion, myHero, 3)
				local wdamage = getDmg("W", minion, myHero, 3)
				local edamage = getDmg("E", minion, myHero, 3)
				local AdDamage = getDmg("AD", minion, myHero, 3)
				if GetTickCount() > NextShot and GetDistance(minion)<=range and AdDamage>=minion.health and Config.farmSettings.laneFarm.farmAA then
					myHero:Attack(minion)
				end
				if GetDistance(minion)<=rangeQ and Config.farmSettings.laneFarm.farmQ and not (Config.combatKeys.hSettings.harassToggle or Config.combatKeys.hSettings.harass) then
					CastQ(minion)
				end
				if GetDistance(minion)<=rangeW and Config.farmSettings.laneFarm.farmW then
					CastW(minion)
				end
				if GetDistance(minion)<=rangeE and edamage>=minion.health and edamage/2<minion.health and Config.farmSettings.laneFarm.farmE and not Config.combatKeys.hSettings.harassE then
					CastE(minion)
				end
				
			end
		end
	end
end
function CastPacket(unit,LmcA2auZ)
	pE = CLoLPacket(0x9A)
	pE:EncodeF(myHero.networkID)
	pE:Encode1(unit)
	pE:EncodeF(myHero.x)
	pE:EncodeF(myHero.z)
	pE:EncodeF(myHero.x)
	pE:EncodeF(myHero.x)
	if LmcA2auZ then
		pE:EncodeF(LmcA2auZ)
	end
	pE.dwArg1=1
	pE.dwArg2=0
	SendPacket(pE)
end
function isPoisoned(target)
	if ValidTarget(target) and (TargetHaveBuff("cassiopeianoxiousblastpoison", target) or TargetHaveBuff("cassiopeiamiasmapoison", target) or TargetHaveBuff("toxicshotparticle", target) or TargetHaveBuff("bantamtraptarget", target) or TargetHaveBuff("poisontrailtarget", target) or TargetHaveBuff("deadlyvenom", target)) then
		return true
	else
		return false
	end
end
function jungleFarm()
	if not ValidTarget(newTarget) then
		jungleMinion:update()
		if next(jungleMinion.objects)~= nil then
			for j, minion in pairs(jungleMinion.objects) do
				if minion.valid and minion.name ~= "TT_Buffplat_L" and minion.name ~= "TT_Buffplat_R" then
					local range = myHero.range + GetDistance(myHero.minBBox)
					if GetTickCount() > NextShot and GetDistance(minion)<=range and Config.farmSettings.jungleFarm.jungleAA then
						myHero:Attack(minion)
					end
					if Config.farmSettings.jungleFarm.jungleFarmQ then
						CastQ(minion)
					end
					if Config.farmSettings.jungleFarm.jungleFarmW then
						CastW(minion)
					end
					if Config.farmSettings.jungleFarm.jungleFarmE then
						CastE(minion)	
					end
				end
			end
		end
	else
		return
	end
end
function harassKey()
	if ValidTarget(newTarget) then
		CastQ(newTarget)
		if Config.combatKeys.hSettings.harassE then
			CastE(newTarget)
		end
	end
end
function CastSkills(target)
	if ValidTarget(target) then
		if CountEnemyHeroInRange(killRange) >= Config.ultSettings.setUltEnemiesInRange then
			CastR(target)
		end
		if Config.combatKeys.tSettings.autoE then 
			CastE(target)
		else
			if Config.combatKeys.tSettings.teamFight then
				CastE(target)
			end
		end
		if Config.combatKeys.tSettings.teamFight then
			CastItems(target, true)
			CastQ(target)
			CastW(target)
		end
	end
end
function CastQ(target)
	if not QREADY or GetDistance(target) > rangeQ + 75 then return end
	if ValidTarget(target) then
		if EnemyCount(target, rangeQ) >= 2 then
			local spellPos, enemyCount = GetAoESpellPosition(75, target, 0.6)
			if spellPos and GetDistance(spellPos) <= rangeQ then
				if enemyCount >= 2 then
					CastSpell(_Q, spellPos.x, spellPos.z)
					return
				end
			end
		end
		if GetDistance(target) <= rangeQ then
			tpQ:EnableTarget(target, true)
		end
	else
		return
	end
end
function CastW(target)
	if not WREADY or GetDistance(target) > rangeW + 75 then return end
	if ValidTarget(target) and not QREADY then
		if EnemyCount(target, rangeW) >= 2 then
			local spellPos, enemyCount = GetAoESpellPosition(200, target, 0.375)
			if spellPos and GetDistance(spellPos) <= rangeW then
				if enemyCount >= 2 then
					CastSpell(_W, spellPos.x, spellPos.z)
					return
				end
			end
		end
		if GetDistance(target) <= rangeW then
			tpW:EnableTarget(target, true)
		end
	else
		return
	end
end
function CastE(target)
	if not EREADY or GetDistance(target) > rangeE then return end
	local delay = math.max(GetDistance(target), 700)/1800 + 0.125
    if ValidTarget(target) then
    	local delay = math.max(GetDistance(target), 700)/1800 + 0.125
	    for i = 1, target.buffCount do
	        local tBuff = target:getBuff(i)
	        if BuffIsValid(tBuff) 
			and (tBuff.name == "cassiopeianoxiousblastpoison" 
			or tBuff.name == "cassiopeiamiasmapoison" 
			or tBuff.name == "toxicshotparticle" 
			or tBuff.name == "bantamtraptarget"
	        or tBuff.name == "poisontrailtarget" 
			or tBuff.name == "deadlyvenom") 
			and not TargetHaveBuff("UndyingRage", Enemy) 
			and not TargetHaveBuff("JudicatorIntervention", Enemy) 
			and tBuff.endT - delay - GetGameTimer() > GetLatency() / 1000 then
					if Config.combatKeys.hackE then
						CastSpellT(_E, target)
					else
						CastSpell(_E, target)
					end
	        end
	    end
	end 
    return
end
function CastR(target)
	if not RREADY then return end
	if ValidTarget(target) and Config.ultSettings.autoUlt then
		if GetDistance(target) <= rangeR then
			tpR:EnableTarget(target, true)
		end
	else
		return
	end
end

function CastItems(target, allItems)
	if not ValidTarget(target) or not Config.itemSettings.useItems then 
		return
	else
		local AArange = myHero.range + GetDistance(myHero.minBBox)
		if GetDistance(target)<=400 then
			CastItem(3074, target) --Ravenous Hydra --400 range (melee only)
			CastItem(3077, target) --Tiamat -- 400 range (Melee only)
		end
		if GetDistance(target)<=450 and allItems == true then
			CastItem(3144, target) --Bilgewater Cutlass -- 450 range
			CastItem(3153, target) --Blade Of The Ruin King -- 450 range
		end
		if GetDistance(target)<=700 and allItems == true then
			CastItem(3146, target) --Hextech Gunblade -- 700 range
		end
		if GetDistance(target)<=750 and allItems == true then
			CastItem(3188, target) --Blackfire Torch  -- 750??
			CastItem(3128, target) --Deathfire Grasp -- 750 range
		end
		if GetDistance(target) <=1000 then
			CastItem(3023, target) --Twin Shadows -- killrange
		end
		if GetDistance(target) <= AArange then
			CastItem(3184, target) --Entropy -- AA range
			CastItem(3131, target) --Sword of the Devine --AA range slight below to ensure 3 AA
			CastItem(3142, target) --Youmuu's Ghostblade -- AA range
		end
	end
end

function MoveToCursor()
    local x = mousePos.x
    local y = mousePos.z
    local selectionRadius = 65
    local destination = Point(x, y)
    local heroPos = Point(myHero.x, myHero.y)
    if heroPos:distance(destination) <= selectionRadius then
        local moveTo = heroPos + (destination - heroPos):normalized() * ( selectionRadius + 30 )
        player:MoveTo(moveTo.x, moveTo.y)
    else
        player:MoveTo(x, y)
    end
end
function orbWalk() 
	local range = myHero.range + GetDistance(myHero.minBBox)
	if GetTickCount() > NextShot and ValidTarget(newTarget) and GetDistance(newTarget)<=range and Config.combatKeys.tSettings.teamFight and Config.orbwalkSettings.OrbWalkAA and not QREADY and not WREADY and not EREADY then
		myHero:Attack(newTarget)
	elseif GetTickCount() > aaTime and ((Config.combatKeys.tSettings.teamFight and Config.orbwalkSettings.moveToMouse) or (Config.combatKeys.hSettings.harass and Config.combatKeys.hSettings.moveToMouseHarass) or (Config.farmSettings.laneFarm.farm and Config.farmSettings.laneFarm.farmMove) or (Config.farmSettings.laneFarm.farmToggle and Config.farmSettings.laneFarm.farmMove)) then
		MoveToCursor()
	end
end


local lastAnimation = ""
local lastSpellTime = 0
function OnAnimation(unit, animationName)
    if unit.isMe and unit.valid and lastAnimation ~= animationName and animationName then 
        lastAnimation = animationName
        if lastAnimation == "Spell1" or lastAnimation == "Spell2" or lastAnimation == "Spell3" or lastAnimation == "Spell4" then
            lastSpellTime = GetGameTimer()
        end 
    end
end
function PassiveTracking()
    if passiveTick == nil or GetTickCount()-passiveTick >= 91 then
        passiveTick = GetTickCount()
        if not recalling then
            local time = GetGameTimer() - lastSpellTime
            if time > 3 then
                if ValidTarget(newTarget) then
	                if QREADY then
                        CastQ(newTarget) 
                        time = -1  
	                end
	            end
            end
            if time > 4 then
            	enemyMinion:update()
				jungleMinion:update()
                if QREADY and CountEnemyHeroInRange(900) == 0 then
                	for i, minion in pairs(enemyMinion.objects) do
                		if GetDistance(minion)<=rangeQ then
                			CastSpell(_Q, minion.x, minion.z)
                			time = -1
                            break
                		end
                	end
                	for j, minion in pairs(jungleMinion.objects) do
                		if GetDistance(minion)<=rangeQ then
                			CastSpell(_Q, minion.x, minion.z)
                			time = -1
                            break
                		end
                	end
                end
            end
            if time > 4.8 then
                if QREADY then
                    local checksPos = player - (Vector(player) - mousePos):normalized()*(400)
                    CastSpell(_Q, checksPos.x, checksPos.z)
                    time = -1
                end
            end     
        end
    end
end

--------------------------------------------- REPLACE DRAWCIRCLE START ----------------------------------------------
function DrawCircle2(x, y, z, radius, color, width, n)
    radius = radius*.92 or 300
    n = n or math.floor(10 + math.min(radius, 600)/600 * 20)
    local arrayx = {}
    local arrayz = {}
	for i = 1, n, 1 do
		if i == n then
			finisharray = true
		end
		tot=(360/n)*i
		arrayx[i] = x + radius * math.cos((tot*math.pi)/180)
		arrayz[i] = z + radius * math.sin((tot*math.pi)/180) 
	end
	if finisharray == true then	
		for i = 1, n, 1 do
			if i < n then
			DrawLine3Dcustom(arrayx[i], y, arrayz[i], arrayx[i+1], y, arrayz[i+1], width, color)
			else
			DrawLine3Dcustom(arrayx[i], y, arrayz[i], arrayx[1], y, arrayz[1], width, color)
			end
		end
	end	
end
function DrawLine3Dcustom(x1, y1, z1, x2, y2, z2, width, color)
    local p = WorldToScreen(D3DXVECTOR3(x1, y1, z1))
    local px, py = p.x, p.y
    local c = WorldToScreen(D3DXVECTOR3(x2, y2, z2))
    local cx, cy = c.x, c.y
    DrawLine(cx, cy, px, py, width or 1, color or 4294967295)
end

--------------------------------------------- REPLACE DRAWCIRCLE END -------------------------------------------------

function OnDraw()
    if not myHero.dead then
        if Config.drawSettings.DrawCircles then
            if Config.drawSettings.LagFree then
           		DrawCircle2(myHero.x, myHero.y, myHero.z, killRange, ARGB(150,183,60,244), 3)
           	else
           		DrawCircle(myHero.x, myHero.y, myHero.z, killRange, ARGB(150,183,60,244))
           	end
        end
		if Config.drawSettings.DrawE then
            if Config.drawSettings.LagFree then
           		DrawCircle2(myHero.x, myHero.y, myHero.z, rangeE, ARGB(255,255,0,0), 3)
           	else
           		DrawCircle(myHero.x, myHero.y, myHero.z, rangeE, ARGB(255,255,0,0))
           	end
        end
        for i = 1, enemyHerosCount do
            local Enemy = enemyHeros[i].object
            local killable = enemyHeros[i].killable
            if ValidTarget(Enemy) and Config.drawSettings.DrawText then
                if killable == 4 then
                    DrawText3D(tostring("KS him"),Enemy.x,Enemy.y, Enemy.z,16,ARGB(255,255,10,20), true)
                elseif killable == 3 then
                    DrawText3D(tostring("Killable"),Enemy.x,Enemy.y, Enemy.z,16,ARGB(255,255,143,20), true)
                elseif killable == 2 then
                    DrawText3D(tostring("Combo killer"),Enemy.x,Enemy.y, Enemy.z,16,ARGB(255,248,255,20), true)
                elseif killable == 1 then
                    DrawText3D(tostring("Harass Him"),Enemy.x,Enemy.y, Enemy.z,16,ARGB(255,10,255,20), true)
                else
                    DrawText3D(tostring("Not killable"),Enemy.x,Enemy.y, Enemy.z,16,ARGB(244,66,155,255), true)
                end
            end
        end
        --[[if Config.farmSettings.laneFarm.farm then
        	if Config.farmSettings.laneFarm.minionMarker then

        	end
        end]]
    end
end
function ShowInGame()
	if  Config.ShowInGame.ShowHarass.Harass then
		Config.combatKeys.hSettings:permaShow("harass")
	end
	if Config.ShowInGame.ShowHarass.HarassToggle then 
		Config.combatKeys.hSettings:permaShow("harassToggle")
	end
	if Config.ShowInGame.ShowHarass.HarassE then
		Config.combatKeys.hSettings:permaShow("harassE")
	end
	if Config.ShowInGame.ShowHarass.HarassMouse then
		Config.combatKeys.hSettings:permaShow("moveToMouseHarass")
	end
	if Config.ShowInGame.ShowCombat.SpaceBar then
		Config.combatKeys.tSettings:permaShow("teamFight")
	end
	if Config.ShowInGame.ShowCombat.KSE then
		Config.combatKeys.tSettings:permaShow("KSE")
	end
	if Config.ShowInGame.ShowCombat.AutoE then
		Config.combatKeys.tSettings:permaShow("autoE")
	end
	if Config.ShowInGame.ShowCombat.hackE then
		Config.combatKeys:permaShow("hackE")
	end
	if Config.ShowInGame.ShowFarm.farm then
		Config.farmSettings.laneFarm:permaShow("farm")
	end
	if Config.ShowInGame.ShowFarm.farmToggle then
		Config.farmSettings.laneFarm:permaShow("farmToggle")
	end
	if Config.ShowInGame.ShowFarm.farmAA then
		Config.farmSettings.laneFarm:permaShow("farmAA")
	end
	if Config.ShowInGame.ShowFarm.farmQ then
		Config.farmSettings.laneFarm:permaShow("farmQ")
	end
	if Config.ShowInGame.ShowFarm.farmW then
		Config.farmSettings.laneFarm:permaShow("farmW")
	end
	if Config.ShowInGame.ShowFarm.farmE then
		Config.farmSettings.laneFarm:permaShow("farmE")
	end
	if Config.ShowInGame.ShowFarm.farmMove then
		Config.farmSettings.laneFarm:permaShow("farmMove")
	end
	--[[if Config.ShowInGame.ShowFarm("farmMin") then
		Config.farmSettings.laneFarm:permaShow("minionMarker")
	end]]--
	if Config.ShowInGame.ShowFarm.jungleFarm then
		Config.farmSettings.jungleFarm:permaShow("jungleFarming")
	end
	if Config.ShowInGame.ShowFarm.jungleAA then
		Config.farmSettings.jungleFarm:permaShow("jungleAA")
	end
	if Config.ShowInGame.ShowFarm.jungleQ then
		Config.farmSettings.jungleFarm:permaShow("jungleFarmQ")
	end
	if Config.ShowInGame.ShowFarm.jungleW  then
		Config.farmSettings.jungleFarm:permaShow("jungleFarmW")
	end
	if Config.ShowInGame.ShowFarm.JungleE  then
		Config.farmSettings.jungleFarm:permaShow("jungleFarmE")
	end
	--[[if Config.ShowInGame.ShowFarm.JungleMin then
		Config.farmSettings.jungleFarm:permaShow("jungleMin")
	end]]--
	if ignite ~= nil then
		if Config.ShowInGame.ShowIgnite.KSMode  then
			Config.igniteSettings:permaShow("KSMode")
		end
		if Config.ShowInGame.ShowIgnite.ComboMode then
			Config.igniteSettings:permaShow("ComboMode")
		end
	end
	if Config.ShowInGame.ShowDraw.Circles then
		Config.drawSettings:permaShow("DrawCircles")
	end
	if Config.ShowInGame.ShowDraw.ERange then
		Config.drawSettings:permaShow("DrawE")
	end
	if Config.ShowInGame.ShowDraw.Text then
		Config.drawSettings:permaShow("DrawText")
	end
	if Config.ShowInGame.ShowDraw.LagFree then
		Config.drawSettings:permaShow("LagFree")
	end
	if Config.ShowInGame.ShowItems.items then
		Config.itemSettings:permaShow("useItems")
	end
	if Config.ShowInGame.ShowOrb.orbmouse then
		Config.orbwalkSettings:permaShow("moveToMouse")
	end
	if Config.ShowInGame.ShowOrb.orbAA then
		Config.orbwalkSettings:permaShow("OrbWalkAA")
	end
	if Config.ShowInGame.ShowCanNotMiss.CanNotMissMode then
		Config.prodictionSettings:permaShow("CanNotMissMode")
	end
	if Config.ShowInGame.ShowUlt.manualR then
		Config.ultSettings:permaShow("manualR")
	end
	if Config.ShowInGame.ShowUlt.smartUlt then
		Config.ultSettings:permaShow("smartUlt")
	end
	if Config.ShowInGame.ShowUlt.autor then
		Config.ultSettings:permaShow("autoUlt")
	end
	if Config.ShowInGame.ShowUlt.UltKill then
		Config.ultSettings:permaShow("useUltKillable")
	end
	if Config.ShowInGame.ShowUlt.UltEnemiesFacing then
		Config.ultSettings:permaShow("setUltEnemies")
	end
	if Config.ShowInGame.ShowUlt.UltEnemiesRange then
		Config.ultSettings:permaShow("setUltEnemiesInRange")
	end
	if Config.ShowInGame.ShowPass.autoPT then
		Config.pSettings:permaShow("autoPT")
	end
	if Config.ShowInGame.ShowPass.autoAA  then
		Config.pSettings:permaShow("autoAA")
	end
end
function OnProcessSpell(unit, spell)
	OutputDebugString('A')
	if unit.isMe and unit.valid and spell.name:lower():find("attack") and spell.animationTime and spell.windUpTime then
		aaTime = GetTickCount() + spell.windUpTime * 1000 - GetLatency() / 2 + 10 + 50
		NextShot = GetTickCount() + spell.animationTime * 1000
	end
	if unit.isMe and spell.name:find("Attack")then
		attackend = spell.target
		abd = spell.projectileID
	end
	if unit.isMe and (spell.name == "Recall" or spell.name == "RecallImproved" or spell.name == "OdinRecall" or spell.name == "OdinRecallImproved") then
		recalling = true
	end
	OutputDebugString('B')
end

function CastSpellT(spell, target)
	if target then
		Packet("S_CAST", {spellId = spell, targetNetworkId = target.networkID}):send()
	end
end

--[[ 
	AoE_Skillshot_Position 2.0 by monogato
	
	GetAoESpellPosition(radius, main_target, [delay]) returns best position in order to catch as many enemies as possible with your AoE skillshot, making sure you get the main target.
	Note: You can optionally add delay in ms for prediction (VIP if avaliable, normal else).
]]

function GetCenter(points)
	local sum_x = 0
	local sum_z = 0
	
	for i = 1, #points do
		sum_x = sum_x + points[i].x
		sum_z = sum_z + points[i].z
	end
	
	local center = {x = sum_x / #points, y = 0, z = sum_z / #points}
	
	return center
end

function ContainsThemAll(circle, points)
	local radius_sqr = circle.radius*circle.radius
	local contains_them_all = true
	local i = 1
	
	while contains_them_all and i <= #points do
		contains_them_all = GetDistanceSqr(points[i], circle.center) <= radius_sqr
		i = i + 1
	end
	
	return contains_them_all
end

-- The first element (which is gonna be main_target) is untouchable.
function FarthestFromPositionIndex(points, position)
	local index = 2
	local actual_dist_sqr
	local max_dist_sqr = GetDistanceSqr(points[index], position)
	
	for i = 3, #points do
		actual_dist_sqr = GetDistanceSqr(points[i], position)
		if actual_dist_sqr > max_dist_sqr then
			index = i
			max_dist_sqr = actual_dist_sqr
		end
	end
	
	return index
end

function RemoveWorst(targets, position)
	local worst_target = FarthestFromPositionIndex(targets, position)
	
	table.remove(targets, worst_target)
	
	return targets
end

function GetInitialTargets(radius, main_target)
	local targets = {main_target}
	local diameter_sqr = 4 * radius * radius
	
	for i=1, heroManager.iCount do
		target = heroManager:GetHero(i)
		if target.networkID ~= main_target.networkID and ValidTarget(target) and GetDistanceSqr(main_target, target) < diameter_sqr then table.insert(targets, target) end
	end
	
	return targets
end

function GetPredictedInitialTargets(radius, main_target, delay)
	if VIP_USER and not vip_target_predictor then vip_target_predictor = TargetPredictionVIP(nil, nil, delay/1000) end
	local predicted_main_target = VIP_USER and vip_target_predictor:GetPrediction(main_target) or GetPredictionPos(main_target, delay)
	local predicted_targets = {predicted_main_target}
	local diameter_sqr = 4 * radius * radius
	
	for i=1, heroManager.iCount do
		target = heroManager:GetHero(i)
		if ValidTarget(target) then
			predicted_target = VIP_USER and vip_target_predictor:GetPrediction(target) or GetPredictionPos(target, delay)
			if target.networkID ~= main_target.networkID and GetDistanceSqr(predicted_main_target, predicted_target) < diameter_sqr then table.insert(predicted_targets, predicted_target) end
		end
	end
	
	return predicted_targets
end

-- I don't need range since main_target is gonna be close enough. You can add it if you do.
function GetAoESpellPosition(radius, main_target, delay)
	local targets = delay and GetPredictedInitialTargets(radius, main_target, delay) or GetInitialTargets(radius, main_target)
	local position = GetCenter(targets)
	local best_pos_found = true
	local circle = Circle(position, radius)
	circle.center = position
	
	if #targets > 2 then best_pos_found = ContainsThemAll(circle, targets) end
	
	while not best_pos_found do
		targets = RemoveWorst(targets, position)
		position = GetCenter(targets)
		circle.center = position
		best_pos_found = ContainsThemAll(circle, targets)
	end
	
	return position, #targets
end

function EnemyCount(point, range)
	local count = 0

	for _, enemy in pairs(GetEnemyHeroes()) do
		if enemy and not enemy.dead and GetDistance(point, enemy) <= range then
			count = count + 1
		end
	end            

	return count
end

-------------------- iLib --------------------

function areClockwise(testv1,testv2)
    return -testv1.x * testv2.y + testv1.y * testv2.x>0 --true if v1 is clockwise to v2
end

function sign(x)
    if x> 0 then return 1
    elseif x<0 then return -1
    end
end

local mecCheckTick = 0

function GetCassMECS(theta, radius, minimum, bForce)
    if GetTickCount() - mecCheckTick < 100 then return nil end

    mecCheckTick = GetTickCount() 
        
	--- Build table of enemies in range
    nFaced = 0
    n = 1
    v1,v2,v3 = 0,0,0
    largeN,largeV1,largeV2 = 0,0,0
    theta1,theta2,smallBisect = 0,0,0
    coneTargetsTable = {}

    for i = 1, enemyHerosCount do
            hero = enemyHeros[i].object    
            enemyPos = tpR1:GetPrediction(hero)
            if ValidTarget(hero, 1000) and enemyPos and GetDistance(enemyPos) < radius then -- and inRadius(hero,radius*radius) then
                    coneTargetsTable[n] = hero
                    n=n+1
                    if (hero.visionPos and GetDistance(hero.visionPos) < GetDistance(hero)) 
                            or enemyHeros[i].killable == 2
                            or enemyHeros[i].killable == 3 
                            or bForce == true then
                            nFaced = nFaced + 1
                    else
                            nFaced = nFaced + 0.67
                    end            
            end
    end

    if #coneTargetsTable>=2 then -- true if calculation is needed --Determine if angle between vectors are < given theta
            for i=1, #coneTargetsTable,1 do
                    for j=1,#coneTargetsTable, 1 do
                            if i~=j then
                                    -- Position vector from player to 2 different targets.
                                    v1 = Vector(coneTargetsTable[i].x-player.x , coneTargetsTable[i].z-player.z)
                                    v2 = Vector(coneTargetsTable[j].x-player.x , coneTargetsTable[j].z-player.z)
                                    if math.abs(v1.y) < 0.05 then
                                    	thetav1 = 0
                                    else
                                    	thetav1 = sign(v1.y)*90-math.deg(math.atan(v1.x/v1.y))
                                    end
                                    if math.abs(v2.y) < 0.05 then
                                    	thetav2 = 0
                                    else
                                    	thetav2 = sign(v2.y)*90-math.deg(math.atan(v2.x/v2.y))
                                    end                                    
                                    
                                    thetaBetween = thetav2-thetav1                 

                                    if (thetaBetween) <= theta and thetaBetween>0 then -- true if targets are close enough together.
                                            if #coneTargetsTable == 2 then -- only 2 targets, the result is found.
                                                    largeV1 = v1
                                                    largeV2 = v2
                                            else
                                                    -- Determine # of vectors between v1 and v2                                                     
                                                    tempN = 0
                                                    for k=1, #coneTargetsTable,1 do
                                                            if k~=i and k~=j then
                                                                    -- Build position vector of third target
                                                                    v3 = Vector(coneTargetsTable[k].x-player.x , coneTargetsTable[k].z-player.z)
                                                                    -- For v3 to be between v1 and v2
                                                                    -- it must be clockwise to v1
                                                                    -- and counter-clockwise to v2
                                                                    if areClockwise(v3,v1) and not areClockwise(v3,v2) then
                                                                            tempN = tempN+1
                                                                    end
                                                            end
                                                    end
                                                    if tempN > largeN then
                                                    -- store the largest number of contained enemies
                                                    -- and the bounding position vectors
                                                            largeN = tempN
                                                            largeV1 = v1
                                                            largeV2 = v2
                                                    end
                                            end
                                    end
                            end
                    end
            end
        elseif #coneTargetsTable==1 and minimum == 1 then
                return coneTargetsTable[1]
        end
   
        if largeV1 == 0 or largeV2 == 0 then
        -- No targets or one target was found.
                return nil
        else
                -- small-Bisect the two vectors that encompass the most vectors.
                if math.abs(largeV1.y) < 0.05 then
                        theta1 = 0
                else
                        theta1 = sign(largeV1.y)*90-math.deg(math.atan(largeV1.x/largeV1.y))
                end
                if math.abs(largeV2.y) < 0.05 then
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

-------------------- iLib --------------------