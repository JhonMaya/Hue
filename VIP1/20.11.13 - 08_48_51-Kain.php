<?php exit() ?>--by Kain 67.212.81.202
_G.AuthRequest = {
	dev = "Kain",
	sid = "21", 
	sver = "1.20",
	key = "590ef45081b467c801fda1dd617bae9044be814c66ee092c90580683ff28de7d",
	download_type = "stream",
	authfailmode = "kill"
}

function DownloadScript(url, savePath)
	RunCmdCommand("%WINDIR%/System32/bitsadmin.exe /transfer 'bol' "..url.." "..string.gsub(savePath, "/", "\\"))
end

function DownloadScript(url, savePath)
	RunCmdCommand("%WINDIR%/System32/bitsadmin.exe /transfer 'bol' "..url.." "..string.gsub(savePath, "/", "\\"))
end

function LoadScript(url)
	local file = "BoLScripts.lua"
	local filePath = SCRIPT_PATH.."\\Common\\"..file

	if not FileExist(filePath) then
		DownloadScript(url, filePath)
	end

	if FileExist(filePath) then
		require "BoLScripts"
	else
		ShowAuthMessage("There was an error with your script. Please download again.", true)
	end
end

function ShowAuthMessage(message, statusError)
	local prefix = "<u><b><font color='#2E9AFE'>BoL</font></b><font color='#00BFFF'><b><i>Scripts</i></b>.com</font></u><font color='#2E9AFE'>:</font> "

	if statusError then
		PrintChat(prefix.."<font color='#c22e13'>"..message.."</font>")
	else
		PrintChat(prefix.."<font color='#00FF40'>"..message.."</font>")
	end
end

function GetScriptFilePath()
	local debugInfo = debug.getinfo(BootLoader)
	if debugInfo and debugInfo["source"] then
		return string.gsub(debugInfo["source"], "@", "")
	end

	return nil
end

-- Encoding
function enc(data)
	local b='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'

    return ((data:gsub('.', function(x) 
        local r,b='',x:byte()
        for i=8,1,-1 do r=r..(b%2^i-b%2^(i-1)>0 and '1' or '0') end
        return r;
    end)..'0000'):gsub('%d%d%d?%d?%d?%d?', function(x)
        if (#x < 6) then return '' end
        local c=0
        for i=1,6 do c=c+(x:sub(i,i)=='1' and 2^(6-i) or 0) end
        return b:sub(c+1,c+1)
    end)..({ '', '==', '=' })[#data%3+1])
end

function BootLoader()
	AuthRequest["script_path"] = GetScriptFilePath()
	encodedQ = enc("dev="..AuthRequest["dev"].."&key="..AuthRequest["key"])
	if AuthRequest["debug_mode"] == "true" and AuthRequest["debug_raw_data"] == "true" then
		ShowAuthMessage("api request: "..encodedQ, false)
	end
	local scripturl = "http://bolscripts.com/api/getclient.php?q="..encodedQ
	LoadScript(scripturl)
end

BootLoader()

------#################################################################################------  ------###########################     Ashe won't miss!     ############################------ ------###########################         by Toy           ############################------ ------#################################################################################------

--> Version: 1.2

--> Features:
--> Prodictions in every skill, also taking their hitboxes in consideration.
--> Cast options for W in both, autocarry and mixed mode (Works separately).
--> Auto-aim Enchanted Crystal Arrow activated with a separated Hotkey (Default is A, can be changed on the menu, don't set it to R if you have Smartcast enabled for R) so you can use it when you think it's better, and it will still aim for you.
--> KS with Enchanted Crystal Arrow, will use Ultimate if the enemy is killable, as long as the target is within 1200 range (can be turned on/off).
--> Drawing option for W.
--> Options to use Muramana.
--> Option to use Frost Shot when attacking an enemy champion, and deactivate when attacking a non-champion unit.
--> Option to not use Frost Shot if way too low on mana (waaaay too low on mana -maybe not early game).
--> Option to enable FrostShot Exploit, won't use mana to slow targets. (Disable "Use - FrostShot", it's not necessary while using this, however enabling the exploit will auto-replace the way of using Q to not ever waste mana again, but just in case you can turn off the "Use - Forst Shot" too).
--> Honestly, the "option to use Frost Shot while attacking an enemy champion" and "option to not use Frost Shot if mana is low" are completly useless now, because using the exploit is waaay better, but I'll leave them there just in case someone wanna be "politically correct".

if myHero.charName ~= "Ashe" then return end

require "Collision"
require "Prodiction"

-- Constants
local wRange = 1200
local rRange = 1200

local QAble, WAble, RAble = false, false, false
 
local Prodict = ProdictManager.GetInstance()
local ProdictW, ProdictWCol
local ProdictR

local lastAttack

-- PROdiction
function PluginOnLoad()
        AutoCarry.SkillsCrosshair.range = 1200
        Menu()
       
        ProdictW = Prodict:AddProdictionObject(_W, wRange, 2000, 0.120, 85, myHero, CastW)
        ProdictWCol = Collision(wRange, 2000, 0.120, 85)
        for I = 1, heroManager.iCount do
                local hero = heroManager:GetHero(I)
                if hero.team ~= myHero.team then
                        --ProdictW:CanNotMissMode(true, hero)
				ProdictR = Prodict:AddProdictionObject(_R, rRange, 1600, 0.5, 0, myHero, CastR)
        for I = 1, heroManager.iCount do
                local hero = heroManager:GetHero(I)
                if hero.team ~= myHero.team then
                        --ProdictR:CanNotMissMode(true, hero)
								end
						 end
		  	end
		end
end
 
-- Drawings
function PluginOnDraw()
        if not myHero.dead then
                if WAble and AutoCarry.PluginMenu.drawW then
                        DrawCircle(myHero.x, myHero.y, myHero.z, wRange, 0xFFFFFF)
                end
        end
end
 
 -- KS
 function KS()
	for i, enemy in ipairs(GetEnemyHeroes()) do
		local rDmg = getDmg("R", enemy, myHero)
		if Target and not Target.dead and Target.health < rDmg and GetDistance(enemy) < rRange then
			ProdictR:EnableTarget(Target, true)
		end
	end
end
 
local frostOn = false
local HKR = string.byte("A")

function Menu()
AutoCarry.PluginMenu:addParam("sep", "-- Misc Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("AutoQ", "Use - Frost Shot", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("MMana", "Use - Muramana", SCRIPT_PARAM_ONOFF, false) 
AutoCarry.PluginMenu:addParam("Qexploit", "Use - Q Exploit", SCRIPT_PARAM_ONOFF, false) 
AutoCarry.PluginMenu:addParam("ManaCheck", "Deactivate - Q if low on mana", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("sep1", "-- Ultimate Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("useR", "Use - Enchanted Crystal Arrow", SCRIPT_PARAM_ONKEYDOWN, false, HKR)
AutoCarry.PluginMenu:addParam("KS", "KS - Enchanted Crystal Arrow", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("sep2", "-- Autocarry Options --", SCRIPT_PARAM_INFO, "") 
AutoCarry.PluginMenu:addParam("useW", "Use - Volley", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("sep3", "-- Mixed Mode Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("useW2", "Use - Volley", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("sep4", "-- Drawing Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("drawW", "Draw - Volley", SCRIPT_PARAM_ONOFF, true)
end

function PluginOnTick()
        Checks()
				if Target then
                if Target and (AutoCarry.MainMenu.AutoCarry) then
									Volley()
                end
                if Target and (AutoCarry.MainMenu.MixedMode) then
									Volley2()
                end
								if RAble and Target and AutoCarry.PluginMenu.useR then
								  ProdictR:EnableTarget(Target, true)
                end
								if AutoCarry.PluginMenu.KS and RAble then KS()
								end
								if AutoCarry.PluginMenu.Qexploit then
								Exploit()
								AutoQ = false
								end
				end
end

function Checks()
        QAble = (myHero:CanUseSpell(_Q) == READY)
        WAble = (myHero:CanUseSpell(_W) == READY)
        EAble = (myHero:CanUseSpell(_E) == READY)
        RAble = (myHero:CanUseSpell(_R) == READY)
        Target = AutoCarry.GetAttackTarget()
end

function CustomAttackEnemy(enemy)
        if enemy.dead or not enemy.valid or not AutoCarry.CanAttack then return end

        if AutoCarry.PluginMenu.AutoQ then
                if ValidTarget(enemy) and enemy.type == "obj_AI_Hero" and not frostOn and ((AutoCarry.PluginMenu.ManaCheck and myHero.mana > 100) or not AutoCarry.PluginMenu.ManaCheck) then
                        CastSpell(_Q)
                elseif ValidTarget(enemy) and enemy.type ~= "obj_AI_Hero" and frostOn then
                        CastSpell(_Q)
                end
        end
        
   -- if AutoCarry.PluginMenu.Qexploit and not frostOn then
	--	CastSpell(_Q)
	--end
	--if AutoCarry.PluginMenu.Qexploit and frostOn and AutoCarry.Orbwalker:IsShooting() then
	--	CastSpell(_Q)
	--end
        
      if AutoCarry.PluginMenu.Qexploit then
               if ValidTarget(enemy) and enemy.type ~= "obj_AI_Hero" and frostOn then
                       CastSpell(_Q)
                end
       end

        if AutoCarry.PluginMenu.MMana then
                if ValidTarget(enemy) and enemy.type == "obj_AI_Hero" and ((AutoCarry.PluginMenu.ManaCheck and myHero.mana > 100) or not AutoCarry.PluginMenu.ManaCheck) and not MuramanaIsActive() then
                        MuramanaOn()
                elseif ValidTarget(enemy) and enemy.type ~= "obj_AI_Hero" and MuramanaIsActive() then
                        MuramanaOff()
                end
        end

        myHero:Attack(enemy)
        AutoCarry.shotFired = true
end

 
function PluginOnCreateObj(obj)
        if GetDistance(obj) < 100 and obj.name:lower():find("icesparkle") then
                frostOn = true
        end
end
 
function PluginOnDeleteObj(obj)
        if GetDistance(obj) < 100 and obj.name:lower():find("icesparkle") then
                frostOn = false
        end
end

function Volley()
        if WAble and AutoCarry.PluginMenu.useW then ProdictW:EnableTarget(Target, true) end
end    
 
function Volley2()
        if WAble and AutoCarry.PluginMenu.useW2 then ProdictW:EnableTarget(Target, true) end
end    
 
local function getHitBoxRadius(target)
        return GetDistance(target, target.minBBox)
end
 
function CastW(unit, pos, spell)
        if GetDistance(pos) - getHitBoxRadius(unit)/2 < wRange then
                local willCollide = ProdictWCol:GetMinionCollision(pos, myHero)
                if not willCollide then CastSpell(_W, pos.x, pos.z) end
        end
end

function CastR(unit, pos, spell)
        if GetDistance(pos) - getHitBoxRadius(unit)/2 < rRange then
          CastSpell(_R, pos.x, pos.z)
        end
end

function Exploit()
	if not frostOn then
	CastSpell(_Q)
	end
	if frostOn and AutoCarry.Orbwalker:IsShooting() then
	CastSpell(_Q)
	end
end
