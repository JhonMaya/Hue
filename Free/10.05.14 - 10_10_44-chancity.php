<?php exit() ?>--by chancity 96.229.9.229
--[[Variables]]--
local SkillQ = 0
local SkillW = 0
local SkillE = 280

--[[AutoUpdate Settings, turn update to false to turn off]]--
local hasUpdated = true
local curVersion = 1.22
local GetVersionURL = "http://bit.ly/18y0Y91"
local newDownloadURL = nil
local newVersion = nil
local newMessage = nil
local SCRIPT_PATH = BOL_PATH.."Scripts\\Common\\SidasAutoCarryPlugin - Nidalee.lua"
local VER_PATH = os.getenv("APPDATA").."\\NidaleeVersion.ini"

DownloadFile(GetVersionURL, VER_PATH, function() end)


--[[Menu]]--
function PluginOnLoad()
Menu = AutoCarry.PluginMenu
Menu:addParam("sep", "[Nidalee Auto Carry: Version "..curVersion.."]", SCRIPT_PARAM_INFO, "")
Menu:addParam("space", "", SCRIPT_PARAM_INFO, "")
Menu:addParam("sep", "[Heal Options]", SCRIPT_PARAM_INFO, "")
Menu:addParam("HealAll", "Heal Everyone", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("K"))
Menu:addParam("HealHealth", "Heal if below X% health", SCRIPT_PARAM_SLICE, .7, .1, 1, .9)
Menu:addParam("HealMana", "Heal if mana is above X%", SCRIPT_PARAM_SLICE, .5, .1, 1, 1)
Menu:addParam("ForceHeal", "Change form to heal", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("space1", "", SCRIPT_PARAM_INFO, "")
Menu:addParam("sep1", "[Cougar Options]", SCRIPT_PARAM_INFO, "")
Menu:addParam("QHealth", "% of enemy health to cast Q", SCRIPT_PARAM_SLICE, 0.7, 0.1, 1, 1)
Menu:addParam("AutoCougar", "Automatically change to cougar", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("UseW", "Use W in Combo", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("UseE", "Use E to farm with Lane Clear", SCRIPT_PARAM_ONOFF, true)
end
--[[PluginOnTick]]--
function PluginOnTick()

	if hasUpdated then 
		if FileExist(VER_PATH) then
			AutoUpdate() 
		end 
	end

	ReadyChecks()
	Heal()

	if AutoCarry.MainMenu.AutoCarry and Target then
			if NidaleeForm then
			--[[Human Combo]]--
			        --if WREADY then AutoCarry.CastSkillshot(SkillW, Target) end
				if not QREADY and GetDistance(Target) <= 500 and Menu.AutoCougar then CastSpell(_R) end
			else
			--[[Cougar Combo]]--
				if QREADY and (Target.health / Target.maxHealth) < Menu.QHealth then CastSpell(_Q) end
				if Menu.UseW and WREADY and GetDistance(Target) < SkillW then CastSpell(_W) end
				if EREADY and GetDistance(Target) < SkillE then CastSpell(_E) end
			end
		end
		
	if AutoCarry.MainMenu.LaneClear and Menu.UseE and NidaleeForm == false then
			for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
				if ValidTarget(minion) and GetDistance(minion) <= SkillE then CastSpell(_E) end
			end
	end	
end

function ReadyChecks()
--[[Checks to see if skills are ready for use]]--
Target = AutoCarry.GetAttackTarget()
QREADY = (myHero:CanUseSpell(_Q) == READY )
WREADY = (myHero:CanUseSpell(_W) == READY )
EREADY = (myHero:CanUseSpell(_E) == READY )
RREADY = (myHero:CanUseSpell(_R) == READY )

--[[Checks Nidalee's current form]]--
	if myHero:GetSpellData(_Q).name == "JavelinToss"  then
		NidaleeForm = true
		SkillW = {spellKey = _W, range = 900, speed = 1, delay = 250, width = 110}
		AutoCarry.SkillsCrosshair.range = 1450
	elseif myHero:GetSpellData(_Q).name == "Takedown"  then 
		NidaleeForm = false 
		SkillQ = myHero.range + GetDistance(myHero, myHero.minBBox)
		SkillW = 375
		AutoCarry.SkillsCrosshair.range = SkillW
	end
end

--[[Heal function]]--
function Heal()
--[[Force heal change form]]--
	if NidaleeForm == false and Menu.ForceHeal and myHero.health <= myHero.maxHealth*Menu.HealHealth and myHero.mana >= myHero.maxMana*Menu.HealMana and RREADY then CastSpell(_R) end
--[[Heal self if HealAll = false]]--
	if myHero.mana >= myHero.maxMana*Menu.HealMana then
		if Menu.HealAll == false and NidaleeForm then
			if myHero.health <= myHero.maxHealth*Menu.HealHealth then
				if myHero:CanUseSpell(_E) == READY then CastSpell(_E, myHero) end
			end
--[[Heal everyone and self if HealAll = true]]--
		elseif Menu.HealAll and NidaleeForm then
			for i=1, heroManager.iCount do
			local allytarget = heroManager:GetHero(i)
				if allytarget.team == myHero.team and not allytarget.dead then 
					if GetDistance(allytarget) <= 600 and allytarget.health <= allytarget.maxHealth*Menu.HealHealth then
						if myHero:CanUseSpell(_E) == READY then CastSpell(_E, allytarget) end
					end
				end
			end
		end
	end
end

function NewIniReader()
	local reader = {};
	function reader:Read(fName)
		self.root = {};
		self.reading_section = "";
		for line in io.lines(fName) do
			if startsWith(line, "[") then
				local section = string.sub(line,2,-2);
				self.root[section] = {};
				self.reading_section = section;
			elseif not startsWith(line, ";") then
				if self.reading_section then
					local var,val = line:split("=");
					local var,val = var:trim(), val:trim();
					if string.find(val, ";") then
						val,comment = val:split(";");
						val = val:trim();
					end
					self.root[self.reading_section] = self.root[self.reading_section] or {};
					self.root[self.reading_section][var] = val;
				else
					return error("No element set for setting");
				end
			end
		end
	end
	function reader:GetValue(Section, Key)
		return self.root[Section][Key];
	end
	function reader:GetKeys(Section)
		return self.root[Section];
	end
	return reader;
end

function startsWith(text,prefix)
	return string.sub(text, 1, string.len(prefix)) == prefix
end

function string:split(sep)
	return self:match("([^" .. sep .. "]+)[" .. sep .. "]+(.+)")
end

function string:trim()
	return self:match("^%s*(.-)%s*$")
end

function AutoUpdate()
	reader = NewIniReader();
	
	if FileExist(VER_PATH) then 
		reader:Read(VER_PATH);
	
		newDownloadURL = reader:GetValue("Version", "Download")
		newVersion = reader:GetValue("Version", "Version")
		newMessage = reader:GetValue("Version", "Message")
		
		local results, reason = os.remove(VER_PATH)
		if results then
			PrintChat("<font color='#e066a3'> >> Nidalee Auto Carry Plugin:</font> <font color='#f4cce0'> Checking for update... </font>")
		
		end
		
		PrintChat("<font color='#e066a3'> >> Nidalee Auto Carry Plugin:</font> <font color='#f4cce0'> Running Version "..curVersion.."</font>")
		if tonumber(newVersion) > tonumber(curVersion) then
			PrintChat("<font color='#e066a3'> >> Nidalee Auto Carry Plugin:</font> <font color='#f4cce0'> New Version Released "..newVersion.."</font>")
			
			os.remove(SCRIPT_PATH)
			DownloadFile(newDownloadURL, SCRIPT_PATH, function()
			if FileExist(SCRIPT_PATH) then
                PrintChat("<font color='#e066a3'> >> Nidalee Auto Carry Plugin:</font> <font color='#f4cce0'> Updated to version "..newVersion..", press F9 two times to use updated script </font>")
            end
			end)
		else
			PrintChat("<font color='#e066a3'> >> Nidalee Auto Carry Plugin:</font> <font color='#f4cce0'> Script is Up-To-Date </font>")
		end	
		PrintChat("<font color='#e066a3'> >> Nidalee Auto Carry Plugin:</font> <font color='#f4cce0'> Update Message ("..newVersion.."): "..newMessage.."</font>")
		
	else 
		PrintChat("<font color='#e066a3'> >> Nidalee Auto Carry Plugin:</font> <font color='#f4cce0'> Failed to check for update </font>")
	end 
	hasUpdated = false
end

