<?php exit() ?>--by Incognito 70.78.192.224

--[[
	Combo Damage Calculation 1.4
		by eXtragoZ
	
	Features:
		- Prints near every enemy the percentage of current life they make to you with a combo of abilities, attacks and items
		- Prints near every enemy the percentage of current life you make to them with a combo of abilities, attacks and items
		- The combos are based on a time limit of 5 seconds
		- "D:" means Does
		- "R:" means Receive
		- The colors of the text change according to the percentage
		- Press shift to configure
	Todo:
		- Implement Liandry's Torment and Blackfire Torch for spells
		- Implement Muramana for a single target spells
		- Improve the combos
]]
_G.DrawCircle = function() end
championCombo = {
	Aatrox = {
		combo1 = "HITdmgSec*3+HITdmg*2*RREADY+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Ahri = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg3READY+Edmg1READY+Rdmg1READY*3",
	},
	Akali = {
		combo1 = "HITdmg+Pdmg1+Qdmg3READY+Edmg1READY+Rdmg1READY*3",
	},
	Alistar = {
		combo1 = "HITdmg+Pdmg3+Qdmg1READY+Wdmg1READY",
	},
	Amumu = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY*5+Edmg1READY+Rdmg1READY",
	},
	Anivia = {
		combo1 = "HITdmg+Qdmg3READY+Edmg3READY+Rdmg1READY*5",
	},
	Annie = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY+Rdmg1READY+Rdmg2READY",
	},
	Ashe = {
		combo1 = "HITdmgSec*(4-WREADY/2-RREADY/2)+Wdmg1READY+Rdmg1READY",
	},
	Blitzcrank = {
		combo1 = "HITdmg+Qdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Brand = {
		combo1 = "HITdmg+Pdmg1*5+Qdmg1READY+Wdmg3READY+Edmg1READY+Rdmg3READY",
	},
	Caitlyn = {
		combo1 = "HITdmgSec*(5-Q1hit-E1hit-R1hit)+Pdmg1+Qdmg1READY*Q1hit+Edmg1READY*E1hit+Rdmg1READY*R1hit",
	},
	Cassiopeia = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY*4+Edmg1READY*4+Rdmg1READY",
	},
	Chogath = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY+Edmg1+Rdmg1READY",
	},
	Corki = {
		combo1 = "(HITdmgSec+Pdmg1*HITSpeed)*5+Qdmg1READY+Edmg1READY*4+Rdmg1READY",
	},
	Darius = {
		combo1 = "HITdmgSec*(4-R3hit)+Pdmg1*4+Qdmg3READY+Wdmg1READY+Rdmg3READY*R3hit",
	},
	Diana = {
		combo1 = "HITdmg*3+Pdmg1+Qdmg1READY+Wdmg3READY+Rdmg1READY*(1+QREADY)",
	},
	DrMundo = {
		combo1 = "HITdmg*2+Qdmg1READY+Wdmg1READY*5",
	},
	Draven = {
		combo1 = "HITdmgSec*(5-E1hit)+Pdmg1*2+Qdmg1READY*2+Edmg1READY*E1hit+Rdmg1READY*1.92",
	},
	Elise = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY", -- range
		combo2 = "HITdmg*3+Pdmg1*3+QMdmg1READY+Rdmg1READY*3", -- melee
	},
	Evelynn = {
		combo1 = "HITdmg+Qdmg1READY*4+Edmg1READY+Rdmg1READY",
	},
	Ezreal = {
		combo1 = "HITdmgSec*(5-R1hit)+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg1READY*R1hit",
	},
	FiddleSticks = {
		combo1 = "HITdmg+Wdmg3READY+Edmg1READY*2+Rdmg3READY",
	},
	Fiora = {
		combo1 = "HITdmgSec*3+Qdmg1READY*2+Wdmg1READY+Rdmg3READY",
	},
	Fizz = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY*2+Edmg1READY+Rdmg1READY",
	},
	Galio = {
		combo1 = "HITdmg+Qdmg1READY+Edmg1READY+Rdmg3READY",
	},
	Gangplank = {
		combo1 = "HITdmgSec*3+Pdmg1*3+Qdmg1READY+Rdmg1READY*5",
	},
	Garen = {
		combo1 = "HITdmgSec*3+Qdmg1READY+Edmg3READY+Rdmg1READY",
	},
	Gragas = {
		combo1 = "HITdmg+Qdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Graves = {
		combo1 = "HITdmgSec*(5-R1hit)+Qdmg3READY+Wdmg1READY+Rdmg1READY*R1hit",
	},
	Hecarim = {
		combo1 = "HITdmgSec*3+Qdmg1READY+Wdmg3READY+Edmg3READY+Rdmg3READY",
	},
	Heimerdinger = {
		combo1 = "HITdmg+Qdmg1READY*4+Wdmg1READY+Edmg1READY",
	},
	Irelia = {
		combo1 = "HITdmg*3+Qdmg1READY+Wdmg1READY*3+Edmg1READY+Rdmg1READY*4",
	},
	Janna = {
		combo1 = "HITdmg+Qdmg3READY+Wdmg1READY",
	},
	JarvanIV = {
		combo1 = "HITdmgSec*3+Pdmg1+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Jax = {
		combo1 = "HITdmgSec*4+Qdmg1READY+Wdmg1READY+Edmg3READY+Rdmg1READY",
	},
	Jayce = {
		combo1 = "HITdmgSec*4+Qdmg1READY*(1+.4*EREADY)+Rdmg1READY", -- range
		combo2 = "HITdmg*3+QMdmg1READY+WMdmg3READY+EMdmg1READY", -- melee
	},
	Karma = {
		combo1 = "HITdmg*2+Qdmg1READY+(Qdmg2READY+Qdmg3READY)*RREADY+Wdmg1READY",
	},
	Karthus = {
		combo1 = "HITdmg+Qdmg1READY*2+Edmg1READY*5+Rdmg1READY",
	},
	Kassadin = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Katarina = {
		combo1 = "HITdmg+Qdmg3READY+Wdmg1READY+Edmg1READY+Rdmg1READY*10",
	},
	Kayle = {
		combo1 = "HITdmgSec*5+Qdmg1READY+Edmg1READY*4",
	},
	Kennen = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg3READY",
	},
	Khazix = {
		combo1 = "HITdmg*3+Pdmg1+Qdmg3READY+Wdmg1READY+Edmg1READY",
	},
	KogMaw = {
		combo1 = "(HITdmgSec+Wdmg1*HITSpeed)*(5-R1hit)+Qdmg1READY+Edmg1READY+Rdmg1READY*2*R1hit",
	},
	Leblanc = {
		combo1 = "HITdmg+Qdmg3READY+Wdmg1READY+Edmg3READY+(Qdmg3*(1+Rdmg1/100))*RREADY",
	},
	LeeSin = {
		combo1 = "HITdmgSec*3+Qdmg3READY+Edmg1READY+Rdmg1READY",
	},
	Leona = {
		combo1 = "HITdmg*2+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Lissandra = {
		combo1 = "HITdmg*2+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Lulu = {
		combo1 = "HITdmg+Pdmg3+Qdmg1READY+Edmg1READY",
	},
	Lux = {
		combo1 = "HITdmg+Pdmg1*2+Qdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Malphite = {
		combo1 = "HITdmg*2+Qdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Malzahar = {
		combo1 = "HITdmg+Pdmg1*2+Qdmg1READY+Wdmg1READY*5+Edmg1READY+Rdmg1READY",
	},
	Maokai = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY+Edmg3READY+Rdmg1READY",
	},
	MasterYi = {
		combo1 = "HITdmgSec*4+Qdmg1READY",
	},
	MissFortune = {
		combo1 = "(HITdmgSec+Wdmg1*HITSpeed)*(5-E1hit-R3hit)+Qdmg1READY+Edmg1READY*E1hit+Rdmg3READY*R3hit",
	},
	Mordekaiser = {
		combo1 = "HITdmg+Qdmg3READY+Wdmg1READY*4+Edmg1READY+Rdmg1READY",
	},
	Morgana = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg3READY+Rdmg3READY",
	},
	Nami = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY+Edmg1READY*3+Rdmg1READY",
	},
	Nasus = {
		combo1 = "HITdmgSec*(4-E3hit)+Qdmg1READY+Edmg3READY*E3hit+Rdmg1READY*5",
	},
	Nautilus = {
		combo1 = "HITdmg*2+Pdmg1+Qdmg1READY+Wdmg1READY*2+Edmg3READY+Rdmg1READY",
	},
	Nidalee = {
		combo1 = "HITdmgSec*(5-Q1hit-W1hit)+Qdmg1READY*Q1hit+Wdmg1READY*W1hit", -- range 
		combo2 = "HITdmgSec*(4-E1hit)+QMdmg1READY+WMdmg1READY+EMdmg1READY*E1hit", -- melee
	},
	Nocturne = {
		combo1 = "HITdmgSec*(4-R1hit)+Pdmg1+Qdmg1READY+Edmg1READY+Rdmg1READY*R1hit",
	},
	Nunu = {
		combo1 = "HITdmg+Edmg1READY+Rdmg1READY",
	},
	Olaf  = {
		combo1 = "HITdmgSec*(4-Q1hit)+Qdmg1READY*Q1hit+Edmg1READY",
	},
	Orianna = {
		combo1 = "HITdmg+Pdmg1+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Pantheon = {
		combo1 = "HITdmgSec*(4-E3hit)+Qdmg1READY+Wdmg1READY+Edmg3READY*E3hit",
	},
	Poppy = {
		combo1 = "(HITdmgSec*4+Qdmg1READY+Edmg3READY)*(1+Rdmg1READY/100)",
	},
	Quinn = {
		combo1 = "HITdmgSec*4+Pdmg1*(1+EREADY)+Qdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Rammus = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY*(2+2*EREADY)+Rdmg1READY*5",
	},
	Renekton = {
		combo1 = "HITdmgSec*(4-W3hit)+Qdmg3READY+Wdmg3READY*W3hit+Edmg1READY*2+Rdmg1READY*5",
	},
	Rengar = {
		combo1 = "HITdmgSec*(4-Q1hit-E1hit)+Qdmg1READY*Q1hit+Qdmg3READY+Wdmg1READY+Edmg1READY*E1hit",
	},
	Riven = {
		combo1 = "HITdmgSec*3+Pdmg1*3+Qdmg1READY*3+Wdmg1READY+Rdmg1READY",
	},
	Rumble = {
		combo1 = "HITdmg+Pdmg1+Qdmg3READY*1.25+Edmg1READY*2+Rdmg3READY",
	},
	Ryze = {
		combo1 = "HITdmg+Qdmg1READY+Qdmg1*math.max(WREADY*EREADY,WREADY*RREADY,EREADY*RREADY)+Wdmg1READY+Edmg3READY",
	},
	Sejuani = {
		combo1 = "HITdmg*2+Qdmg1READY+Wdmg1READY+Wdmg3READY+Edmg1READY+Rdmg1READY",
	},
	Shaco = {
		combo1 = "HITdmgSec*4+Qdmg1READY+Wdmg1READY*5+Edmg1READY+Rdmg1READY+(HITdmg*2.25*RREADY)",
	},
	Shen = {
		combo1 = "HITdmgSec*4+Pdmg1+Qdmg1READY+Edmg1READY",
	},
	Shyvana = {
		combo1 = "HITdmgSec*4+Qdmg1READY+Wdmg1READY*5+Edmg1READY*1.45+Rdmg1READY",
	},
	Singed = {
		combo1 = "HITdmg+Qdmg1READY*5+Edmg1READY",
	},
	Sion = {
		combo1 = "HITdmgSec*4+Qdmg1READY+Wdmg1READY",
	},
	Sivir = {
		combo1 = "HITdmgSec*4+Qdmg1READY*1.8+Wdmg1READY",
	},
	Skarner = {
		combo1 = "HITdmgSec*(4-E1hit-R1hit)+Qdmg1READY*2+Edmg1READY*E1hit+Rdmg1READY*R1hit",
	},
	Sona = {
		combo1 = "HITdmg+Pdmg3+Qdmg1READY+Rdmg1READY",
	},
	Soraka = {
		combo1 = "HITdmg+Qdmg1READY*2+Edmg1READY",
	},
	Swain = {
		combo1 = "(HITdmg+Qdmg3READY+Wdmg1READY+Edmg1READY+Rdmg1READY*4)*(1+Edmg2READY/100)",
	},
	Syndra = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg1READY*5",
	},
	Talon = {
		combo1 = "(HITdmgSec*3+Qdmg1READY+Wdmg3READY+Rdmg3READY)*(1+Edmg1READY/100)",
	},
	Taric = {
		combo1 = "HITdmg+Pdmg1+Wdmg1READY+Edmg3READY+Rdmg1READY",
	},
	Teemo = {
		combo1 = "(HITdmgSec+Edmg1*HITSpeed)*5+Qdmg1READY+Edmg2*5",
	},
	Thresh = {
		combo1 = "HITdmg*2+Qdmg1READY+Qdmg2+Edmg1READY+Rdmg1READY",
	},
	Tristana = {
		combo1 = "HITdmgSec*5+Wdmg1READY+Edmg1READY+Rdmg1READY",
	},
	Trundle = {	
		combo1 = "HITdmgSec*4+Qdmg1READY+Rdmg1READY",
	},
	Tryndamere = {
		combo1 = "HITdmgSec*4+Edmg1READY",
	},
	TwistedFate = {
		combo1 = "HITdmgSec*(4-Q1hit)+Qdmg1READY*Q1hit+Wdmg3READY+Edmg1",
	},
	Twitch = {
		combo1 = "HITdmgSec*(5-E3hit)+Pdmg1*6*6+Edmg3READY*E3hit",
	},
	Udyr = {
		combo1 = "HITdmgSec*3+Qdmg1READY+Rdmg3READY+Rdmg2READY",
	},
	Urgot = {
		combo1 = "HITdmgSec*4+Qdmg1READY*2+Edmg1READY",
	},
	Varus = {
		combo1 = "(HITdmgSec+Wdmg1*HITSpeed)*(5-RREADY)+Qdmg1READY+Wdmg3+Edmg1READY+Rdmg1READY",
	},
	Vayne = {
		combo1 = "HITdmgSec*5+Qdmg1READY+Wdmg1READY+Edmg3READY",
	},
	Veigar = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY+Rdmg1READY",
	},
	Vi = {
		combo1 = "HITdmgSec*(4-R1hit)+Qdmg3READY+Wdmg1READY+Edmg1READY*2+Rdmg1READY*R1hit",
	},
	Viktor = {
		combo1 = "HITdmg+Qdmg1READY+Edmg3READY+Rdmg1READY+Rdmg2READY*5", -- if Augment Death
	},
	Vladimir = {
		combo1 = "(HITdmg+Qdmg1READY+Wdmg1READY+Edmg3READY)*(1+.12*RREADY)+Rdmg1READY", --if max stacks
	},
	Volibear = {
		combo1 = "HITdmg*3+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg1READY*3",
	},
	Warwick = {
		combo1 = "HITdmgSec*(4-R3hit)+Pdmg1*6+Qdmg1READY+Rdmg3READY*R3hit",
	},
	MonkeyKing = {
		combo1 = "HITdmgSec*(4-R3hit)+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg3READY*R3hit",
	},
	Xerath = {
		combo1 = "HITdmg+Qdmg1READY+Edmg1READY+Rdmg1READY*3",
	},
	XinZhao = {
		combo1 = "HITdmgSec*4+Qdmg1READY*3+Edmg1READY+Rdmg1READY",
	},
	Yorick = {
		combo1 = "HITdmgSec*4+Pdmg1*6+Qdmg1READY+Wdmg1READY+Edmg1READY",
	},
	Zac = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY+Edmg1READY+Rdmg3READY",
	},
	Zed = {
		combo1 = "(HITdmgSec*4+Pdmg1+Qdmg3READY+Edmg1READY)*(1+Rdmg2READY/100)+Rdmg1READY",
	},
	Ziggs = {
		combo1 = "HITdmg+Pdmg1+Qdmg1READY+Wdmg1READY+Edmg1READY*2.2+Rdmg1READY",
	},
	Zilean = {
		combo1 = "HITdmg+Qdmg1READY+Qdmg1*WREADY",
	},
		VelKoz = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY*2+Edmg1READY+Rdmg1READY",
	},
	Zyra = {
		combo1 = "HITdmg+Qdmg1READY+Wdmg1READY*4.5+Edmg1READY+Rdmg1READY",
	},
}
local tick
local calculationenemy = 1
local enemycombo = {}
local mycombo = {}
function OnLoad()
	CDCConfig = scriptConfig("Combo Damage Calculation 1.4", "cdcconfig")
	CDCConfig:addParam("drawenemycombo", "Draw Enemy Combo Dmg", SCRIPT_PARAM_ONOFF, true)
	CDCConfig:addParam("drawmycombo", "Draw My Combo Dmg", SCRIPT_PARAM_ONOFF, true)
	CDCConfig:addParam("drawx", "Draw x", SCRIPT_PARAM_SLICE, 150, 0, 200, 0)
	CDCConfig:addParam("drawy", "Draw y", SCRIPT_PARAM_SLICE, 80, 0, 200, 0)
	for i=1, heroManager.iCount do enemycombo[i],mycombo[i] = 0,0 end
	PrintChat(" >> Combo Damage Calculation 1.4 loaded!")
end
function OnTick()
	if myHero.dead then return end
	if tick == nil or GetTickCount()-tick >= 50 then
		tick = GetTickCount()
		local enemy = heroManager:GetHero(calculationenemy)		
		local enemypPos = WorldToScreen(D3DXVECTOR3(enemy.x+CDCConfig.drawx,enemy.maxBBox.y,enemy.z+CDCConfig.drawy))
		if ValidTarget(enemy) and OnScreen(enemypPos.x, enemypPos.y) then
			local combodmg = getEnemyDmg(enemy, myHero)
			local percentcombodmg = myHero.health > 0 and math.round(combodmg*100/myHero.health,0) or 0
			enemycombo[calculationenemy] = percentcombodmg
			local mycombodmg = getEnemyDmg(myHero, enemy)
			local mypercentcombodmg = enemy.health > 0 and math.round(mycombodmg*100/enemy.health,0) or 0
			mycombo[calculationenemy] = mypercentcombodmg
		end
		if calculationenemy == 1 then calculationenemy = heroManager.iCount
		else calculationenemy = calculationenemy-1 end
	end
end
function OnDraw()
	if myHero.dead then return end
	for i=1, heroManager.iCount do
		local enemy = heroManager:GetHero(i)
		if ValidTarget(enemy) then
			local enemypPos = WorldToScreen(D3DXVECTOR3(enemy.x+CDCConfig.drawx,enemy.maxBBox.y,enemy.z+CDCConfig.drawy))
			if OnScreen(enemypPos.x, enemypPos.y) then
				if CDCConfig.drawenemycombo then
					local enemyred = enemycombo[i]<100 and math.floor(enemycombo[i]/100*255) or 255
					local enemygreen = enemycombo[i]<100 and math.floor(255 - enemycombo[i]/100*255) or 0
					DrawText("D:"..enemycombo[i].."%", 20, enemypPos.x, enemypPos.y, RGBA(enemyred,enemygreen,0,255))
				end
				if CDCConfig.drawmycombo then
					local myred = mycombo[i]<100 and math.floor(mycombo[i]/100*255) or 255
					local mygreen = mycombo[i]<100 and math.floor(255 - mycombo[i]/100*255) or 0
					DrawText("R:"..mycombo[i].."%", 20, enemypPos.x, enemypPos.y+20, RGBA(myred,mygreen,0,255))
				end
			end
		end
	end
end
function getEnemyDmg(champion, target)
	local name = champion.charName
	if championCombo[name] == nil then return 0 end
	local combodmg = 0
	local XM = false
	if name == "Jayce" or name == "Nidalee" or name == "Elise" then XM = true end
	-- Items
	local InfinityEdge,onhitdmg,on1hitdmg,itemdmg,onhitspelldmg,sunfiredmg,muramanadmg = 0,0,0,0,0,0,0
	if GetInventoryHaveItem(3031,champion) then InfinityEdge = .5 end
	if GetInventoryHaveItem(3186,champion) then onhitdmg = onhitdmg+getDmg("KITAES",target,champion) end
	if GetInventoryHaveItem(3114,champion) then onhitdmg = onhitdmg+getDmg("MALADY",target,champion) end
	if GetInventoryHaveItem(3091,champion) then onhitdmg = onhitdmg+getDmg("WITSEND",target,champion) end
	if GetInventoryHaveItem(3153,champion) then onhitdmg = onhitdmg+getDmg("RUINEDKING",target,champion) end
	if GetInventoryHaveItem(3042,champion) then
		muramanadmg = getDmg("MURAMANA",target,champion)
		onhitdmg = onhitdmg+muramanadmg
	end
	if GetInventoryHaveItem(3057,champion) then on1hitdmg = on1hitdmg+getDmg("SHEEN",target,champion) end
	if GetInventoryHaveItem(3078,champion) then on1hitdmg = on1hitdmg+getDmg("TRINITY",target,champion) end
	if GetInventoryHaveItem(3100,champion) then on1hitdmg = on1hitdmg+getDmg("LICHBANE",target,champion) end
	if GetInventoryHaveItem(3025,champion) then on1hitdmg = on1hitdmg+getDmg("ICEBORN",target,champion) end
	if GetInventoryHaveItem(3087,champion) then on1hitdmg = on1hitdmg+getDmg("STATIKK",target,champion) end
	if GetInventoryHaveItem(3209,champion) then on1hitdmg = on1hitdmg+getDmg("SPIRITLIZARD",target,champion) end
	if GetInventoryItemIsCastable(3184,champion) then on1hitdmg = on1hitdmg+80 end
	if GetInventoryItemIsCastable(3128,champion) then itemdmg = itemdmg+getDmg("DFG",target,champion) end
	if GetInventoryItemIsCastable(3146,champion) then itemdmg = itemdmg+getDmg("HXG",target,champion) end
	if GetInventoryItemIsCastable(3144,champion) then itemdmg = itemdmg+getDmg("BWC",target,champion) end
	if GetInventoryItemIsCastable(3153,champion) then itemdmg = itemdmg+getDmg("RUINEDKING",target,champion,2) end
	if GetInventoryHaveItem(3151,champion) then onhitspelldmg = getDmg("LIANDRYS",target,champion) end
	if GetInventoryHaveItem(3188,champion) then onhitspelldmg = getDmg("BLACKFIRE",target,champion) end
	if GetInventoryHaveItem(3068,champion) then sunfiredmg = getDmg("SUNFIRE",target,champion) end
	-- Spells READY
	local QREADY = champion:CanUseSpell(_Q) == (champion.isMe and READY or 3)
	local WREADY = champion:CanUseSpell(_W) == (champion.isMe and READY or 3)
	local EREADY = champion:CanUseSpell(_E) == (champion.isMe and READY or 3)
	local RREADY = champion:CanUseSpell(_R) == (champion.isMe and READY or 3)
	-- Spells dmg
	local Pdmg1 = getDmg("P",target,champion)
	local Pdmg2 = getDmg("P",target,champion,2)
	local Pdmg3 = getDmg("P",target,champion,3)
	local Qdmg1 = getDmg("Q",target,champion)
	local Qdmg2 = getDmg("Q",target,champion,2)
	local Qdmg3 = getDmg("Q",target,champion,3)
	local Wdmg1 = getDmg("W",target,champion)
	local Wdmg2 = getDmg("W",target,champion,2)
	local Wdmg3 = getDmg("W",target,champion,3)
	local Edmg1 = getDmg("E",target,champion)
	local Edmg2 = getDmg("E",target,champion,2)
	local Edmg3 = getDmg("E",target,champion,3)
	local Rdmg1 = getDmg("R",target,champion)
	local Rdmg2 = getDmg("R",target,champion,2)
	local Rdmg3 = getDmg("R",target,champion,3)
	local QMdmg1 = XM and getDmg("QM",target,champion) or 0
	local QMdmg2 = XM and getDmg("QM",target,champion,2) or 0
	local QMdmg3 = XM and getDmg("QM",target,champion,3) or 0
	local WMdmg1 = XM and getDmg("WM",target,champion) or 0
	local WMdmg2 = XM and getDmg("WM",target,champion,2) or 0
	local WMdmg3 = XM and getDmg("WM",target,champion,3) or 0
	local EMdmg1 = XM and getDmg("EM",target,champion) or 0
	local EMdmg2 = XM and getDmg("EM",target,champion,2) or 0
	local EMdmg3 = XM and getDmg("EM",target,champion,3) or 0
	-- Spells dmg if READY
	local Qdmg1READY = QREADY and Qdmg1 or 0
	local Qdmg2READY = QREADY and Qdmg2 or 0
	local Qdmg3READY = QREADY and Qdmg3 or 0
	local Wdmg1READY = WREADY and Wdmg1 or 0
	local Wdmg2READY = WREADY and Wdmg2 or 0
	local Wdmg3READY = WREADY and Wdmg3 or 0
	local Edmg1READY = EREADY and Edmg1 or 0
	local Edmg2READY = EREADY and Edmg2 or 0
	local Edmg3READY = EREADY and Edmg3 or 0
	local Rdmg1READY = RREADY and Rdmg1 or 0
	local Rdmg2READY = RREADY and Rdmg2 or 0
	local Rdmg3READY = RREADY and Rdmg3 or 0
	local QMdmg1READY = QREADY and QMdmg1 or 0
	local QMdmg2READY = QREADY and QMdmg2 or 0
	local QMdmg3READY = QREADY and QMdmg3 or 0
	local WMdmg1READY = WREADY and WMdmg1 or 0
	local WMdmg2READY = WREADY and WMdmg2 or 0
	local WMdmg3READY = WREADY and WMdmg3 or 0
	local EMdmg1READY = EREADY and EMdmg1 or 0
	local EMdmg2READY = EREADY and EMdmg2 or 0
	local EMdmg3READY = EREADY and EMdmg3 or 0
	-- HITdmg
		--champion.critDmg
	local HITSpeed = champion.attackSpeed*0.625
	local critdmg = 1+InfinityEdge
	local HITdmg = getDmg("AD",target,champion)*(1+critdmg*champion.critChance)+onhitdmg
	local HITdmgSec = HITdmg*HITSpeed
	-- compare HITdmgSec with spells
	local Q1hit = Qdmg1READY > HITdmgSec and 1 or 0
	local Q2hit = Qdmg2READY > HITdmgSec and 1 or 0
	local Q3hit = Qdmg3READY > HITdmgSec and 1 or 0
	local W1hit = Wdmg1READY > HITdmgSec and 1 or 0
	local W2hit = Wdmg2READY > HITdmgSec and 1 or 0
	local W3hit = Wdmg3READY > HITdmgSec and 1 or 0
	local E1hit = Edmg1READY > HITdmgSec and 1 or 0
	local E2hit = Edmg2READY > HITdmgSec and 1 or 0
	local E3hit = Edmg3READY > HITdmgSec and 1 or 0
	local R1hit = Rdmg1READY > HITdmgSec and 1 or 0
	local R2hit = Rdmg2READY > HITdmgSec and 1 or 0
	local R3hit = Rdmg3READY > HITdmgSec and 1 or 0
	-- combo
	local combo = championCombo[name]["combo1"]
	if XM and (champion:GetSpellData(_R).name == "JayceStanceHtG" or champion:GetSpellData(_R).name == "EliseRSpider" or champion:GetSpellData(_Q).name == "Takedown") then
		combo = championCombo[name]["combo2"]
	end
	local replacetext1 = {"QREADY", "WREADY" ,"EREADY" ,"RREADY", "Qdmg1READY", "Qdmg2READY", "Qdmg3READY", "Wdmg1READY", "Wdmg2READY", "Wdmg3READY", "Edmg1READY", "Edmg2READY", "Edmg3READY", "Rdmg1READY", "Rdmg2READY", "Rdmg3READY"}
	local replaceto1 = {QREADY and 1 or 0, WREADY and 1 or 0, EREADY and 1 or 0, RREADY and 1 or 0,Qdmg1READY, Qdmg2READY, Qdmg3READY, Wdmg1READY, Wdmg2READY, Wdmg3READY, Edmg1READY, Edmg2READY, Edmg3READY, Rdmg1READY, Rdmg2READY, Rdmg3READY}
	local replacetext2 = {"HITSpeed", "HITdmgSec", "HITdmg", "Pdmg1", "Pdmg2", "Pdmg3", "Qdmg1", "Qdmg2", "Qdmg3", "Wdmg1", "Wdmg2", "Wdmg3", "Edmg1", "Edmg2", "Edmg3", "Rdmg1", "Rdmg2", "Rdmg3"}
	local replaceto2 = {HITSpeed, HITdmgSec, HITdmg, Pdmg1, Pdmg2, Pdmg3, Qdmg1, Qdmg2, Qdmg3, Wdmg1, Wdmg2, Wdmg3, Edmg1, Edmg2, Edmg3, Rdmg1, Rdmg2, Rdmg3}
	local replacetext3 = {"QMdmg1READY", "QMdmg2READY", "QMdmg3READY", "WMdmg1READY", "WMdmg2READY", "WMdmg3READY", "EMdmg1READY", "EMdmg2READY", "EMdmg3READY", "QMdmg1", "QMdmg2", "QMdmg3", "WMdmg1", "WMdmg2", "WMdmg3", "EMdmg1", "EMdmg2", "EMdmg3"}
	local replaceto3 = {QMdmg1READY, QMdmg2READY, QMdmg3READY, WMdmg1READY, WMdmg2READY, WMdmg3READY, EMdmg1READY, EMdmg2READY, EMdmg3READY, QMdmg1, QMdmg2, QMdmg3, WMdmg1, WMdmg2, WMdmg3, EMdmg1, EMdmg2, EMdmg3}
	local replacetext4 = {"Q1hit", "Q2hit", "Q3hit", "W1hit", "W2hit", "W3hit", "E1hit", "E2hit", "E3hit", "R1hit", "R2hit", "R3hit"}
	local replaceto4 = {Q1hit, Q2hit, Q3hit, W1hit, W2hit, W3hit, E1hit, E2hit, E3hit, R1hit, R2hit, R3hit}
	if combo ~= nil then
		for i=1, #replacetext1 do combo = combo:gsub(replacetext1[i], replaceto1[i]) end
		for i=1, #replacetext2 do combo = combo:gsub(replacetext2[i], replaceto2[i]) end
		for i=1, #replacetext3 do combo = combo:gsub(replacetext3[i], replaceto3[i]) end
		for i=1, #replacetext4 do combo = combo:gsub(replacetext4[i], replaceto4[i]) end
		combodmg = load("return "..combo)()
	end
	-- IGNITE
	if champion:GetSpellData(SUMMONER_1).name:find("SummonerDot") or champion:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		local ignitedmg = getDmg("IGNITE",target,champion)
		combodmg = combodmg + ignitedmg
	end
	-- add items
		combodmg = combodmg + on1hitdmg + itemdmg + sunfiredmg*4
	-- return
	return combodmg
end