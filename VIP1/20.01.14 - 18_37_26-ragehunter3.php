<?php exit() ?>--by ragehunter3 46.117.73.179
--[[Updater]]--
class 'UpdateLib'

UpdateLib.instance = ''

function UpdateLib:__init(Name)
	self.LocalVersion = 0
	self.SCRIPT_NAME = ""
	self.SCRIPT_URL = ""
	self.PATH = ""
	self.HOST = ""
	self.URL_PATH = ""
	self.NeedUpdate = false
	self.NeedRun = true
	self.instance = Name
end

function UpdateLib.Instance(Name)
	if self.instance == "" then
		self.instance = UpdateLib(Name)
	end

	return self.instance
end

function UpdateLib:Run()
	if self.LocalVersion ~= 0 and self.SCRIPT_NAME ~= "" and self.SCRIPT_URL ~= "" and self.PATH ~= "" and self.HOST ~= "" and self.URL_PATH ~= "" then
		AddTickCallback(function() self:OnTick() end)
	else
		PrintChat("You missed variables. Won't start the update class.")
	end
end

function UpdateLib:OnTick()
	if self.NeedRun then
		self.NeedRun = false
		GetAsyncWebResult(self.HOST, self.URL_PATH, function(Data)
			local OnlineVersion = tonumber(Data)

			if type(OnlineVersion) ~= "number" then return end
			if OnlineVersion and self.LocalVersion and OnlineVersion > self.LocalVersion then
				print("Updater: There is a new version of "..self.SCRIPT_NAME..". Don't F9 till done...") 
				self.NeedUpdate = true
			end
		end)
	end

	if self.NeedUpdate then
		self.NeedUpdate = false
		DownloadFile(self.SCRIPT_URL, self.PATH, function()
			if FileExist(self.PATH) then
				print("Updater: "..self.SCRIPT_NAME.." updated! Double F9 to use new version!")
			end
		end)
	end
end

--[[User Sync]]--
vipauthed = nil
betaauthed = nil
authed = false
GetAsyncWebResult("painenterprises.cuccfree.com","/pain/aiosupport/auth.php","aio="..string.lower(GetUser()),function(msg) if msg == "authed" then betaauthed = true else betaauthed = false end end)
--GetAsyncWebResult("painenterprises.cuccfree.com","/pain/aiosupport/vipauth.php","aio="..string.lower(GetUser()),function(vipmsg) if vipmsg == "authed" then vipauthed = true else vipauthed = false end end)

--[[SCRIPT]]--
function trueRange()
	return myHero.range + GetDistance(myHero.minBBox)
end
local combatTable = {
Orianna =				{
Q 	= {true,925,1800,0.22,90,"Line",2},
W		= {nil,0,nil,nil,nil,nil},
E 		= {nil,trueRange(),nil,nil,nil,nil},
R 		= {false,520,math.huge,0.5,80,"Still",2},
								}, 
Blitzcrank =				{
Q 	= {true,925,1800,0.22,90,"Line",2},
W		= {nil,0,nil,nil,nil,nil},
E 		= {nil,trueRange(),nil,nil,nil,nil},
R 		= {false,520,math.huge,0.5,80,"Still",2},
								}, 
Janna		=				{
Q 	= {false,1300,800,0,200,"Line",2},
W		= {nil,600,nil,nil,nil},
E 		= {nil,0,nil,nil,nil},
R 		= {nil,0,nil,nil,nil},
								},
Karma 		=				{
Q 	= {true,1050,1650,0.265,100,"Line",1},
W		= {nil,675,nil,nil,nil},
E 		= {nil,0,nil,nil,nil},
R 		= {nil,0,nil,nil,nil},
								},
Leona 		=				{
Q 	= {nil,trueRange(),nil,nil,nil},
W		= {nil,450,nil,nil,nil},
E 		= {false,900,2000,0,90,"Line",2},
R 		= {false,1200,math.huge,0.3,120,"Circular",4},
								},
Lulu 		=				{
Q 	= {false,950,1400,0.25,60,"Line",2},
W		= {nil,650,nil,nil,nil},
E 		= {nil,650,nil,nil,nil},
R 		= {nil,0,nil,nil,nil},
								},
--[[Lux 			=				{
Q 	= {true,950,1400,0.25,80,"Line",2						},
W		= {nil,0,nil,nil,nil									},
E 		= {false,1100,1300,0.225,120,"Circular",2				},
R 		= {false,3340,math.huge,0.5,190,"Line",3			},
								},]]
Morgana 	=				{
Q 	= {true,1300,1200,0.225,80,"Line",2},
W		= {false,900,math.huge,0.250,100	,"Circular",4},
E 		= {nil,0,nil,nil,nil},
R 		= {nil,0,nil,nil,nil},
								},
Nami		=				{
Q 	= {false,875,math.huge,1.075,100,"Circular",2},
W		= {nil,725,nil,nil,nil},
E 		= {nil,0,nil,nil,nil},
R 		= {false,2700,850,0.5,150,"Line",3},
								},
Nidalee	 =				{
Q 	= {true,1450,1300,0.25,75,"Line",2},
W		= {false,900,math.huge,0.15,90,"Circular",2},
E 		= {nil,0,nil,nil,nil},
R 		= {nil,0,nil,nil,nil},
								},
Sona		 =				{
Q 	= {nil,650,nil,nil,nil},
W		= {nil,0,nil,nil,nil},
E 		= {nil,0,nil,nil,nil},
R 		= {false,1000,2500,0.25,150,"Line",3},
},
Soraka = {
Q = {nil,675,nil,nil,nil},
W = {nil,0,nil,nil,nil},
E	= {nil,750,nil,nil,nil},
R = {nil,0,nil,nil,nil},
},
Thresh 	=				{
Q 	= {true,1100,2000,0.5,90,"Line",2},
W		= {nil,0,nil,nil,nil},
E 		= {false,415,math.huge,1.3,80,"Still",1},
R 		= {false,400,math.huge,0.75,50,"Still",2	},
								},
Zyra			=				{
Q 	= {false,800,1400,0.225,120,"Line",2},
W		= {nil,0,nil,nil,nil},
E 		= {false,1150,950,0.225,80,"Line",2},
R 		= {false,1850,math.huge,0.7,200,"Circular",3},
								},
								} -- Champion Combat Data. 1 = collision, 2 = range, 3 = speed, 4 = delay, 5 = width
local cd = combatTable[myHero.charName]
if cd == nil then print(""..myHero.charName.." is not supported.</font>") print("AIOSupport: Denied") return end
local VP = nil
function OnLoad()
	versionGOE = 1.4
	Update = UpdateLib("AIOSupport")
	Update.LocalVersion = versionGOE
	Update.SCRIPT_NAME = "AIOSupport"
	Update.SCRIPT_URL = "https://bitbucket.org/BoLPain/private-scripts/raw/master/AIOSupport.lua"
	Update.PATH = BOL_PATH.."Scripts\\".."AIOSupport.lua"
	Update.HOST = "painenterprises.cuccfree.com"
	Update.URL_PATH = "/Scripts/Revisions/AIOSupport.lua"
	Update:Run()	
	FakeLoad = true
	lastAttack, lastWindUpTime, lastAttackCD = 0, 0, 0
	TSRange = trueRange()
end
function FakeOnLoad()
	
	VP = VPrediction()
	for i, combat in pairs(combatTable) do
		if combat == cd then
			--Q
			if cd.Q[1] ~= nil then qColChamp = cd.Q[1] end
			if qRange == nil then qRange = cd.Q[2] end
			if qSpeed == nil then qSpeed = cd.Q[3] end
			if qDelay == nil then qDelay = cd.Q[4] end
			if qWidth == nil then qWidth = cd.Q[5] end
			if cd.Q[6] ~= nil then qType = cd.Q[6] end
			--W
			if cd.W[1] ~= nil then wColChamp = cd.W[1] end
			if wRange == nil then wRange = cd.W[2] end
			if wSpeed == nil then wSpeed = cd.W[3] end
			if wDelay == nil then wDelay = cd.W[4] end
			if wWidth == nil then wWidth = cd.W[5] end
			if cd.W[6] ~= nil then wType = cd.W[6] end
			--E
			if cd.E[1] ~= nil then eColChamp = cd.E[1] end
			if eRange == nil then eRange = cd.E[2] end
			if eSpeed == nil then eSpeed = cd.E[3] end
			if eDelay == nil then eDelay = cd.E[4] end
			if eWidth == nil then eWidth = cd.E[5] end
			if cd.E[6] ~= nil then eType = cd.E[6] end
			--R
			if cd.R[1] ~= nil then rColChamp = cd.R[1] end
			if rRange == nil then rRange = cd.R[2] end
			if rSpeed == nil then rSpeed = cd.R[3] end
			if rDelay == nil then rDelay = cd.R[4] end
			if rWidth == nil then rWidth = cd.R[5] end
			if cd.R[6] ~= nil then rType = cd.R[6] end
			
			if cd.Q[7] ~= nil then qHitChance = cd.Q[7] end
			if cd.W[7] ~= nil then wHitChance = cd.W[7] end
			if cd.E[7] ~= nil then eHitChance = cd.E[7] end
			if cd.R[7] ~= nil then rHitChance = cd.R[7] end
			--End
			break
		end
	end --End of for loop
	qHCvar, wHCvar, eHCvar, rHCvar = 0
	ScriptMenu()
	print("AIOSupport requires the use of 1.04 VPrediction (Untested with v1.06 if you have used 1.06 and it works then please post on the forums).")
end
function ScriptMenu()
	Menu = scriptConfig("AIOSupport: "..versionGOE,"AIOS")

	Menu:addSubMenu("AIOSupport: General", "General")
	Menu.General:addParam("Active", "Activate Script", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu.General:addParam("Harass", "Harass Enemy", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))

	Menu:addSubMenu("AIOSupport: Movement Logic", "Move")
	Menu.Move:addParam("Orbwalk", "Orbwalking", SCRIPT_PARAM_ONOFF, false)
	Menu.Move:addParam("mtm","Move To Mouse", SCRIPT_PARAM_ONOFF, false)
	
	Menu:addSubMenu("AIOSupport: Combo Skills", "Skills")
	if qRange > 1 then Menu.Skills:addParam("autoQ", "Automatically use Q", SCRIPT_PARAM_ONOFF, true) end
	if wRange > 1 then Menu.Skills:addParam("autoW", "Automatically use W", SCRIPT_PARAM_ONOFF, true) end
	if eRange > 1 then Menu.Skills:addParam("autoE", "Automatically use E", SCRIPT_PARAM_ONOFF, true) end
	if rRange > 1 then Menu.Skills:addParam("autoR", "Automatically use R", SCRIPT_PARAM_ONOFF, true) end
	
	Menu:addSubMenu("AIOSupport: Harass Skills", "hSkills")
	if qRange > 1 then Menu.hSkills:addParam("HautoQ", "Automatically use Q", SCRIPT_PARAM_ONOFF, false) end
	if wRange > 1 then Menu.hSkills:addParam("HautoW", "Automatically use W", SCRIPT_PARAM_ONOFF, false) end
	if eRange > 1 then Menu.hSkills:addParam("HautoE", "Automatically use E", SCRIPT_PARAM_ONOFF, false) end
	if rRange > 1 then Menu.hSkills:addParam("HautoR", "Automatically use R", SCRIPT_PARAM_ONOFF, false) end
	
	Menu:addSubMenu("AIOSupport: Skill HitChance","HitChance")
	if qType ~= nil then Menu.HitChance:addParam("qHC", "Hit Chance for spell: Q", SCRIPT_PARAM_SLICE, 2,0,5,0) 
	Menu.HitChance:addParam("qHCinfo", "Will use Q if: ", SCRIPT_PARAM_INFO, qHCvar)
	end
	if wType ~= nil then Menu.HitChance:addParam("wHC", "Hit Chance for spell: W", SCRIPT_PARAM_SLICE, 2,0,5,0)
	Menu.HitChance:addParam("wHCinfo", "Will use W if: ", SCRIPT_PARAM_INFO, wHCvar)
	end
	if eType ~= nil then Menu.HitChance:addParam("eHC", "Hit Chance for spell: E", SCRIPT_PARAM_SLICE, 2,0,5,0) 
	Menu.HitChance:addParam("eHCinfo", "Will use E if: ", SCRIPT_PARAM_INFO, eHCvar)
	end
	if rType ~= nil then Menu.HitChance:addParam("rHC", "Hit Chance for spell: R", SCRIPT_PARAM_SLICE, 2,0,5,0)
	Menu.HitChance:addParam("rHCinfo", "Will use R if: ", SCRIPT_PARAM_INFO, rHCvar)
	end
	if qHitChance ~= nil then Menu.HitChance.qHC = qHitChance end
	if wHitChance ~= nil then Menu.HitChance.wHC = wHitChance end
	if eHitChance ~= nil then Menu.HitChance.eHC = eHitChance end
	if rHitChance ~= nil then Menu.HitChance.rHC = rHitChance end
	
	Menu:addSubMenu("AIOSupport: Drawing", "Draw")
	Menu.Draw:addParam("enableDraw", "Enable Drawing", SCRIPT_PARAM_ONOFF, true)
	Menu.Draw:addParam("showCombo", "Show Combo in the Mini-Menu", SCRIPT_PARAM_ONOFF, true)
	Menu.Draw:addParam("showHarass", "Show Harass in the Mini-Menu", SCRIPT_PARAM_ONOFF, true)
	
	if Menu.Draw.showCombo then Menu.General:permaShow("Active") end
	if Menu.Draw.showHarass then Menu.General:permaShow("Harass") end
	
	if iAm == "Thresh" then
	Menu:addSubMenu("AIOSupport: Personal Menu", "Personal")
	Menu.Personal:addParam("eAway", "Cast E away from Thresh", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("T"))
	end
	
	--[[Menu:addSubMenu("AIOSupport: Skill Values", "sDetails")
	Menu.sDetails:addParam("gInfo","These Details are loaded from within the script", SCRIPT_PARAM_INFO,"")
	Menu.sDetails:addParam("qInfo","Skill Details for Skill(_Q)",SCRIPT_PARAM_INFO,"")
	if qRange ~= nil then Menu.sDetails:addParam("qInfo1", "qRange: ", SCRIPT_PARAM_INFO, qRange) end
	if qSpeed ~= nil then Menu.sDetails:addParam("qInfo2", "qSpeed: ", SCRIPT_PARAM_INFO, qSpeed) end
	if qDelay ~= nil then Menu.sDetails:addParam("qInfo3", "qDelay: ", SCRIPT_PARAM_INFO, qDelay) end
	if qWidth ~= nil then Menu.sDetails:addParam("qInfo4", "qWidth: ", SCRIPT_PARAM_INFO, qWidth) end
	Menu.sDetails:addParam("wInfo","Skill Details for Skill(_W)",SCRIPT_PARAM_INFO,"")
	if wRange ~= nil then Menu.sDetails:addParam("wInfo1", "wRange: ", SCRIPT_PARAM_INFO, wRange) end
	if wSpeed ~= nil then Menu.sDetails:addParam("wInfo2", "wSpeed: ", SCRIPT_PARAM_INFO, wSpeed) end
	if wDelay ~= nil then Menu.sDetails:addParam("wInfo3", "wDelay: ", SCRIPT_PARAM_INFO, wDelay) end
	if wWidth ~= nil then Menu.sDetails:addParam("wInfo4", "wWidth: ", SCRIPT_PARAM_INFO, wWidth) end
	Menu.sDetails:addParam("eInfo","Skill Details for Skill(_E)",SCRIPT_PARAM_INFO,"")
	if eRange ~= nil then Menu.sDetails:addParam("eInfo1", "eRange: ", SCRIPT_PARAM_INFO, eRange) end
	if eSpeed ~= nil then Menu.sDetails:addParam("eInfo2", "eSpeed: ", SCRIPT_PARAM_INFO, eSpeed) end
	if eDelay ~= nil then Menu.sDetails:addParam("eInfo3", "eDelay: ", SCRIPT_PARAM_INFO, eDelay) end
	if eWidth ~= nil then Menu.sDetails:addParam("eInfo4", "eWidth: ", SCRIPT_PARAM_INFO, eWidth) end
	Menu.sDetails:addParam("rInfo","Skill Details for Skill(_R)",SCRIPT_PARAM_INFO,"")
	if rRange ~= nil then Menu.sDetails:addParam("rInfo1", "rRange: ", SCRIPT_PARAM_INFO, rRange) end
	if rSpeed ~= nil then Menu.sDetails:addParam("rInfo2", "rSpeed: ", SCRIPT_PARAM_INFO, rSpeed) end
	if rDelay ~= nil then Menu.sDetails:addParam("rInfo3", "rDelay: ", SCRIPT_PARAM_INFO, rDelay) end
	if rWidth ~= nil then Menu.sDetails:addParam("rInfo4", "rWidth: ", SCRIPT_PARAM_INFO, rWidth) end
	
	Menu.sDetails:addParam("","",SCRIPT_PARAM_INFO,"")
	Menu.sDetails:addParam("inf","1.#INF = math.huge",SCRIPT_PARAM_INFO,"")]]
	
	ts = TargetSelector(TARGET_NEAR_MOUSE, TSRange, DAMAGE_MAGICAL, false)
	ts.name = myHero.charName
	Menu:addTS(ts)
end
function OnTick()
	if not vipauthed or not betaauthed then authed = false end
	if vipauthed or betaauthed then authed = true end
	if not authed then return end
	if FakeLoad == true then FakeOnLoad() FakeLoad = false end
	
	if FakeLoad == false then
		Checks()
		ts:update()
		Target = ts.target
		if Target then
			xPos = myHero.x + (myHero.x - ts.target.x)
			zPos = myHero.z + (myHero.z - ts.target.z)
		end
		if Menu.General.Active then
			StutterStep()
			if not Target then return end
			if GetDistance(myHero,Target) <= TSRange then
				CharacterFightingLogics()
			end
		end
		if Menu.General.Harass then
			StutterStep()
			if not Target then return end
			if GetDistance(myHero,Target) <= TSRange then
				CharacterHarassLogics()
			end
		end	
	end	
end
function CharacterFightingLogics()
	if not WrongForm then
		if iAm  == "Blitzcrank" then BlitzcrankCombo() end
		if iAm  == "Janna" then JannaCombo() end
		if iAm  == "Karma" then KarmaCombo() end
		if iAm  == "Leona" then LeonaCombo() end
		if iAm  == "Lulu" then LuluCombo() end
		if iAm  == "Lux" then LuxCombo() end
		if iAm  == "Morgana" then MorganaCombo() end
		if iAm  == "Nami" then NamiCombo() end
		if iAm  == "Nidalee" then NidaleeCombo() end
		if iAm  == "Sona" then SonaCombo() end
		if iAm == "Soraka" then SorakaCombo() end
		if iAm  == "Thresh" then ThreshCombo() end
		if iAm  == "Zyra" then ZyraCombo() end
	end
end
function CharacterHarassLogics()
	if not WrongForm then
		if iAm  == "Blitzcrank" then BlitzcrankHarass() end
		if iAm  == "Janna" then JannaHarass() end
		if iAm  == "Karma" then KarmaHarass() end
		if iAm  == "Leona" then LeonaHarass() end
		if iAm  == "Lulu" then LuluHarass() end
		if iAm  == "Lux" then LuxHarass() end
		if iAm  == "Morgana" then MorganaHarass() end
		if iAm  == "Nami" then NamiHarass() end
		if iAm  == "Nidalee" then NidaleeHarass() end
		if iAm  == "Sona" then SonaHarass() end
		if iAm == "Soraka" then SorakaHarass() end
		if iAm  == "Thresh" then ThreshHarass() end
		if iAm  == "Zyra" then ZyraHarass() end
	end
end

--[[COMBO]]--
function BlitzcrankCombo()
 if QREADY and Menu.Skills.autoQ then qHandler() end
 if EREADY and Menu.Skills.autoE then eHandler() end
 if RREADY and Menu.Skills.autoR then rHandler() end
end
function JannaCombo()
 if QREADY and Menu.Skills.autoQ then qHandler() end
 if EREADY and Menu.Skills.autoE then eHandler() end
end
function KarmaCombo()
 if QREADY and Menu.Skills.autoQ then qHandler() end
 if WREADY and Menu.Skills.autoW then wHandler() end
end
function LeonaCombo()
 if WREADY and Menu.Skills.autoW then wHandler() end
 if EREADY and Menu.Skills.autoE then eHandler() end
 if RREADY and Menu.Skills.autoR then rHandler() end
end
function LuluCombo()
 if QREADY and Menu.Skills.autoQ then qHandler() end
 if WREADY and Menu.Skills.autoW then wHandler() end
 if EREADY and Menu.Skills.autoE then eHandler() end
end
function LuxCombo()
 if QREADY and Menu.Skills.autoQ then qHandler() end
 if EREADY and Menu.Skills.autoE then eHandler() end
 if RREADY and Menu.Skills.autoR then rHandler() end
end
function MorganaCombo()
 if QREADY and Menu.Skills.autoQ then qHandler() end
 if WREADY and Menu.Skills.autoW then wHandler() end
end
function NamiCombo()
 if QREADY and Menu.Skills.autoQ then qHandler() end
 if WREADY and Menu.Skills.autoW then wHandler() end
 if RREADY and Menu.Skills.autoR then rHandler() end
end
function NidaleeCombo()
 if QREADY and Menu.Skills.autoQ then qHandler() end
 if WREADY and Menu.Skills.autoW then wHandler() end
end
function SonaCombo()
 if QREADY and Menu.Skills.autoQ then qHandler() end
 if RREADY and Menu.Skills.autoR then rHandler() end
end
function SorakaCombo()
if QREADY and Menu.Skills.autoQ then qHandler() end
if EREADY and Menu.Skills.autoE then eHandler() end
end
function ThreshCombo()
 if QREADY and Menu.Skills.autoQ then qHandler() end
 if EREADY and Menu.Skills.autoE then eHandler() end
 if RREADY and Menu.Skills.autoR then rHandler() end
end
function ZyraCombo()
 if QREADY and Menu.Skills.autoQ then qHandler() end
 if EREADY and Menu.Skills.autoE then eHandler() end
 if RREADY and Menu.Skills.autoR then rHandler() end
end
--[[HARASS]]--
function BlitzcrankHarass()
 if QREADY and Menu.hSkills.HautoQ then qHandler() end
 if EREADY and Menu.hSkills.HautoE then eHandler() end
 if RREADY and Menu.hSkills.HautoR then rHandler() end
end
function JannaHarass()
 if QREADY and Menu.hSkills.HautoQ then qHandler() end
 if EREADY and Menu.hSkills.HautoE then eHandler() end
end
function KarmaHarass()
 if QREADY and Menu.hSkills.HautoQ then qHandler() end
 if WREADY and Menu.hSkills.HautoW then wHandler() end
end
function LeonaHarass()
 if WREADY and Menu.hSkills.HautoW then wHandler() end
 if EREADY and Menu.hSkills.HautoE then eHandler() end
 if RREADY and Menu.hSkills.HautoR then rHandler() end
end
function LuluHarass()
 if QREADY and Menu.hSkills.HautoQ then qHandler() end
 if WREADY and Menu.hSkills.HautoW then wHandler() end
 if EREADY and Menu.hSkills.HautoE then eHandler() end
end
function LuxHarass()
 if QREADY and Menu.hSkills.HautoQ then qHandler() end
 if EREADY and Menu.hSkills.HautoE then eHandler() end
 if RREADY and Menu.hSkills.HautoR then rHandler() end
end
function MorganaHarass()
 if QREADY and Menu.hSkills.HautoQ then qHandler() end
 if WREADY and Menu.hSkills.HautoW then wHandler() end
end
function NamiHarass()
 if QREADY and Menu.hSkills.HautoQ then qHandler() end
 if WREADY and Menu.hSkills.HautoW then wHandler() end
 if RREADY and Menu.hSkills.HautoR then rHandler() end
end
function NidaleeHarass()
 if QREADY and Menu.hSkills.HautoQ then qHandler() end
 if WREADY and Menu.hSkills.HautoW then wHandler() end
end
function SonaHarass()
 if QREADY and Menu.hSkills.HautoQ then qHandler() end
 if RREADY and Menu.hSkills.HautoR then rHandler() end
end
function SorakaHarass()
if QREADY and Menu.hSkills.HautoQ then qHandler() end
if EREADY and Menu.hSkills.HautoE then eHandler() end
end
function ThreshHarass()
 if QREADY and Menu.hSkills.HautoQ then qHandler() end
 if EREADY and Menu.hSkills.HautoE then eHandler() end
 if RREADY and Menu.hSkills.HautoR then rHandler() end
end
function ZyraHarass()
 if QREADY and Menu.hSkills.HautoQ then qHandler() end
 if EREADY and Menu.hSkills.HautoE then eHandler() end
 if RREADY and Menu.hSkills.HautoR then rHandler() end
end
function qHandler()
	if qDelay ~= nil then
		if qType == "Line" then CastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, qDelay, qWidth, qRange, qSpeed, myHero) end
		if qType == "Circular" then CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(Target, qDelay, qWidth, qRange) end
		if qType == "Still" then CastPosition, HitChance = VP:GetPredictedPos(Target, qDelay) end
		if qType ~= nil then
			if (HitChance >= Menu.HitChance.qHC or HitChance == 0) and (GetDistance(CastPosition) <= qRange) then
				if qColChamp then
					local Col = Collision(qRange, qSpeed, qDelay, qWidth)
					if not Col:GetMinionCollision(CastPosition, myHero) and not Col:GetMinionCollision(Target, myHero) then
						CastSpell(_Q, CastPosition.x, CastPosition.z)
					end
				end
				if not qColChamp then
					CastSpell(_Q, CastPosition.x, CastPosition.z)
				end
			end
		end
	elseif qRange > 1 then
		if GetDistance(Target) < qRange then CastSpell(_Q,Target) end
	end
end
function wHandler()
	if wDelay ~= nil then
		if wType == "Line" then CastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, wDelay, wWidth, wRange, wSpeed, myHero) end
		if wType == "Circular" then CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(Target, wDelay, wWidth, wRange) end
		if wType == "Still" then CastPosition, HitChance = VP:GetPredictedPos(Target, wDelay) end
		if wType ~= nil then
			if (HitChance >= Menu.HitChance.wHC or HitChance == 0) and (GetDistance(CastPosition) <= wRange) then
				if wColChamp then
					local Col = Collision(wRange, wSpeed, wqDelay, wWidth)
					if not Col:GetMinionCollision(CastPosition, myHero) and not Col:GetMinionCollision(Target, myHero) then
						CastSpell(_W, CastPosition.x, CastPosition.z)
					end
				end
				if not wColChamp then
					CastSpell(_W, CastPosition.x, CastPosition.z)
				end
			end
		end
	elseif wRange > 1 then 
		if GetDistance(Target) < wRange then CastSpell(_W,Target) end
	end
end
function eHandler()
	if iAm == "Thresh" then
		Position, HitChance = VP:GetPredictedPos(Target, eDelay)
		if GetDistance(Target, myHero) < eRange then
			if Menu.Personal.eAway then
				CastSpell(_E, Position.x, Position.z)
			end
			if not Menu.Personal.eAway then
                CastSpell(_E, xPos, zPos)
			end
		end
	end
	if myHero.charName == "Lux" then
		if eParticle and GetDistance(Target, eParticle) < 275 and popCheck() then
			CastSpell(_E)
		else
			CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(Target, eDelay, eWidth, eRange) 
			if (HitChance >= Menu.HitChance.eHC or HitChance == 0) and (GetDistance(CastPosition) <= eRange) then
				CastSpell(_E, CastPosition.x, CastPosition.z)
			end
		end
	end
		if iAm == "Lux" or iAm == "Thresh" then return end
		if eDelay ~= nil then
			if eType == "Line" then CastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, eDelay, eWidth, eRange, eSpeed, myHero) end
			if eType == "Circular" then CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(Target, eDelay, eWidth, eRange) end
			if eType == "Still" then CastPosition, HitChance = VP:GetPredictedPos(Target, eDelay) end
			if eType ~= nil then
				if (HitChance >= Menu.HitChance.eHC or HitChance == 0) and GetDistance(CastPosition) <= eRange then
					if eColChamp then
						local Col = Collision(eRange, eSpeed, eDelay, eWidth)
						if not Col:GetMinionCollision(CastPosition, myHero) and  not Col:GetMinionCollision(Target, myHero) then 
							CastSpell(_E, CastPosition.x, CastPosition.z)
						end
					end
					if not eColChamp then
						CastSpell(_E, CastPosition.x, CastPosition.z)
					end
				end
			end
		elseif eRange > 1 and eDelay == nil then 
			if GetDistance(Target) < eRange then CastSpell(_E,Target) end
		end
end
function rHandler()
	if rDelay ~= nil then
		if rType == "Line" then CastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, rDelay, rWidth, rRange, rSpeed, myHero) end
		if rType == "Circular" then CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(Target, rDelay, rWidth, rRange) end
		if rType == "Still" then CastPosition, HitChance = VP:GetPredictedPos(Target, rDelay) end
		if rType ~= nil then
			if (HitChance >= Menu.HitChance.rHC) and (GetDistance(CastPosition) <= rRange) then
				if rColChamp then
					local Col = Collision(rRange, rSpeed, rDelay, rWidth)
					if not Col:GetMinionCollision(CastPosition, myHero) and not Col:GetMinionCollision(Target, myHero) then 
						CastSpell(_R, CastPosition.x, CastPosition.z)
					end
				end
			if not rColChamp then
				CastSpell(_R, CastPosition.x, CastPosition.z)
			end
		end
	end	
	elseif rRange > 1 then 
		if GetDistance(Target) < rRange then CastSpell(_R,Target) end
	end
end

function OnDraw()
	if not authed then return end
	if TSRange == nil then return end
	if Menu.Draw.enableDraw then
		DrawCircle(myHero.x, myHero.y, myHero.z,  TSRange, 0xFF0000)
	end
end

function Checks()
	Move()
	if myHero:GetSpellData(_Q).name ~= "JavelinToss" and iAm == "Nidalee" then
		WrongForm = true
		else
		WrongForm = false
	end
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	WREADY = (myHero:CanUseSpell(_W) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)
	if not QREADY and TSRange == qRange then TSRange = trueRange() end
	if not WREADY and TSRange ==wRange then TSRange = trueRange() end
	if not EREADY and TSRange == eRange then TSRange = trueRange() end
	if not RREADY and TSRange == rRange then TSRange = trueRange() end
	if RREADY and rRange > TSRange then TSRange = rRange end
	if EREADY and eRange > TSRange then TSRange = eRange end
	if WREADY and wRange > TSRange then TSRange = wRange end
	if QREADY and qRange > TSRange then TSRange = qRange end
	ts = TargetSelector(TARGET_LOW_HP_PRIORITY, TSRange, DAMAGE_MAGICAL, false)
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
end
function AAReset()
--[[	if iAm == "Leona" then 
		if Target.canMove then
			if QREADY and Menu.Skills.autoQ then qHandler() end 
		end
	end
	if iAm == "Blitzcrank" then if EREADY and Menu.Skills.autoE then eHandler() end end]]
end
--[[ORBWALKING]]--
local safevar = true
function Move()
	if safevar == true and Menu.Move.Orbwalk then Menu.Move.mtm = false safevar = false end
	if safevar == false and Menu.Move.mtm then Menu.Move.Orbwalk = false safevar = true end
end
function StutterStep()
	if not Target then
		
	end
	if Target then
		if Menu.Move.Orbwalk then
			OrbWalk2()
			OrbWalk()
		end
	else
		if Menu.Move.Orbwalk or Menu.Move.mtm then
			moveToCursor()
		end
	end
end
function OrbWalk()
	if GetDistance(Target,myHero) > trueRange() then return end
		if timeToShoot() then
			myHero:Attack(Target)
		elseif heroCanMove() then
			moveToCursor()
		end
end
function OrbWalk2()
	if GetDistance(Target,myHero) < trueRange() then return end
	for e=1, heroManager.iCount do
	local enemy = heroManager:GetHero(e)
	if ValidTarget(enemy, trueRange()) then
		local distanceenemy = GetDistance(enemy)
		if (distanceenemy <= trueRange()) and (GetDistance(Target,myHero) > trueRange()) then
			if timeToShoot() then
				myHero:Attack(enemy)
			elseif heroCanMove() then
				moveToCursor()
			end
		end
		else
			moveToCursor()
		end
	end
end
function OnProcessSpell(object, spell)
	if object.isMe then
		if spell.name:lower():find("attack") then
			if iAm == "Nami" then
				lastAttack = GetTickCount() - GetLatency()/2
				lastWindUpTime = spell.windUpTime*1100
				lastAttackCD = spell.animationTime*1100	
			else
				lastAttack = GetTickCount() - GetLatency()/2
				lastWindUpTime = spell.windUpTime*1000
				lastAttackCD = spell.animationTime*1000	
			end
		end
	end
end
function OnAnimation(unit, animationName)
	if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end
function heroCanMove()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastWindUpTime + 20)
end
function timeToShoot()
	AAReset()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastAttackCD)
end
function GetPathDistance(targetPosition) -- return true distance based on unit path
	path = Movement:CalculatePath(targetPosition)
	if path == nil or path.points == nil or #path.points == 1 then
		return math.huge
	else
		local distance = 0
		for i, point in ipairs(path.points) do
			if i ~= #path.points then
				distance = distance + path.points[i]:distance(path.points[i+1])
			end
		end
		return distance
	end	
end
function moveToCursor()
	if GetDistance(mousePos) > 1 or lastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
	end	
end
--Love You Hexy--
function OnCreateObj(obj)
	if obj and obj.name:find("LuxLightstrike_tar") then eParticle = obj end
end

function OnDeleteObj(obj)
	if obj and obj.name:find("LuxLightstrike_tar") then eParticle = nil end
end
function popCheck()
	if myHero:GetSpellData(_E).name == "luxlightstriketoggle" then
		return true
	end
	return false
end
----
function math.sign(x)
 if x < 0 then
  return -1
 elseif x > 0 then
  return 1
 else
  return 0
 end
end