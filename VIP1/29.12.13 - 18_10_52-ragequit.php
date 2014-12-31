<?php exit() ?>--by ragequit 71.63.104.40
--[[ The Wonderful Riven ]]


--[[ The Wonderful Riven ]]
--Spacer
--Spacer
--Spacer
--Spacer
--Spacer

--[[ The Wonderful Riven ]]
--Spacer
--Spacer
--Spacer
--Spacer
--Spacer
--Spacer
--Spacer
--Spacer
--Spacer
--Spacer


 if myHero.charName ~= "Riven" then return end

class 'Plugin'

local SkillQ 
local SkillW 
local SkillE 
local SkillR 
local SkillRC
local Target
local QCount = 0
local NextQ = 0
local ShouldCancel = false
local DoQFix = false
local RetreatPos = nil
local DoCombo, DoHarass = false, false
local HasUlt = false
local REndsAt = 0
local BonusD = false
local ComboSelectorActive = true
local HarassSelectorActive = true
local PassiveStacks = 0
local enemyHeroes
local q = 1
local w = 2
local e = 3
local r = 4
local skilllevel = { q,e,w,q,q,r,q,e,q,e,r,e,e,w,w,r,w,w, } 
SACstate = 3
local DoneInit = false
local ToInterrupt = {}
local StunList = {
    { charName = "Caitlyn", spellName = "CaitlynAceintheHole"},
    { charName = "FiddleSticks", spellName = "Crowstorm"},
    { charName = "FiddleSticks", spellName = "DrainChannel"},
    { charName = "Galio", spellName = "GalioIdolOfDurand"},
    { charName = "Karthus", spellName = "FallenOne"},
    { charName = "Katarina", spellName = "KatarinaR"},
    { charName = "Malzahar", spellName = "AlZaharNetherGrasp"},
    { charName = "MissFortune", spellName = "MissFortuneBulletTime"},
    { charName = "Nunu", spellName = "AbsoluteZero"},
    { charName = "Pantheon", spellName = "Pantheon_GrandSkyfall_Jump"},
    { charName = "Shen", spellName = "ShenStandUnited"},
    { charName = "Urgot", spellName = "UrgotSwap2"},
    { charName = "Varus", spellName = "VarusQ"},
    { charName = "Warwick", spellName = "InfiniteDuress"},
    { charName = "MasterYi", spellName = "Meditate"},
    }

local RangeW = 260
minRange = 100
displayRange = 1000
rotateMultiplier = 7
rivencanq = true
Stage = 0
local foundattack = false
local noattack = true
--local needult = false
--local combos1 = true
--local combos2 = false
--local combos3 = false

local QRange, WRange, ERange = 260, 250, 325

jumpspot = {
	{
		pA = {x = 6393.7299804688, y = -63.87451171875, z = 8341.7451171875},
		pB = {x = 6612.1625976563, y = 56.018413543701, z = 8574.7412109375}
	},
	{
		pA = {x = 7041.7885742188, y = 0, z = 8810.1787109375},
		pB = {x = 7296.0341796875, y = 55.610824584961, z = 9056.4638671875}
	},
	{
		pA = {x = 4401.0258789063, y = 54.257415771484, z = 2530.966796875},
		pB = {x = 4219.0786132813, y = 109.35539245605, z = 2492.5520019531}
	},
	{
		pA = {x = 4510.0258789063, y = 54.257415771484, z = 1922.966796875},
		pB = {x = 4675.0786132813, y = 109.35539245605, z = 1919.5520019531}
	},
	{
		pA = {x = 11488.0258789063, y = 54.257415771484, z = 9870.966796875},
		pB = {x = 11499.0786132813, y = 109.35539245605, z = 9735.5520019531}
	},
	{
		pA = {x = 11339.0258789063, y = 54.257415771484, z = 10046.966796875},
		pB = {x = 11345.0786132813, y = 109.35539245605, z = 10163.5520019531}
	},
	{
		pA = {x = 2805.4074707031, y = 55.182941436768, z = 6140.130859375},
		pB = {x = 2614.3215332031, y = 60.193073272705, z = 5816.9438476563}
	},
	{
		pA =  {x = 6696.486328125, y = 61.310482025146, z = 5377.4013671875},
		pB = {x = 6868.6918945313, y = 55.616455078125, z = 5698.1455078125}
	},
	{
		pA =  {x = 1677.9854736328, y = 54.923847198486, z = 8319.9345703125},
		pB = {x = 1270.2786865234, y = 50.334892272949, z = 8286.544921875}
	},
	{
		pA =  {x = 2822.3254394531, y = -58.759708404541, z = 10178.6328125},
		pB = {x = 2465.8962402344, y = 53.364395141602, z = 9974.4677734375}
	},
	{
		pA =  {x = 5102.642578125, y = -62.845260620117, z = 10322.375976563},
		pB = {x = 5483, y = 54.5009765625, z = 10427}
	},
	{
		pA =  {x = 6000.2373046875, y = 39.544124603271, z = 11763.544921875},
		pB = {x = 6056.666015625, y = 54.385917663574, z = 11388.752929688}
	},
	{
		pA =  {x = 1742.34375, y = 53.561042785645, z = 7630.1557617188},
		pB = {x = 1884.5321044922, y = 54.930736541748, z = 7995.1459960938}
	},
	{
		pA =  {x = 3319.087890625, y = 55.027889251709, z = 7472.4760742188},
		pB = {x = 3388.0522460938, y = 54.486026763916, z = 7101.2568359375}
	},
	{
		pA =  {x = 3989.9423828125, y = 51.94282913208, z = 7929.3422851563},
		pB = {x = 3671.623046875, y = 53.906265258789, z = 7723.146484375}
	},
	{
		pA =  {x = 4936.8452148438, y = -63.064865112305, z = 10547.737304688},
		pB = {x = 5156.7397460938, y = 52.951190948486, z = 10853.216796875}
	},
	{
		pA =  {x = 5028.1235351563, y = -63.082695007324, z = 10115.602539063},
		pB = {x = 5423, y = 55.15357208252, z = 10127}
	},
	{
		pA =  {x = 6035.4819335938, y = 53.918266296387, z = 10973.666015625},
		pB = {x = 6385.4013671875, y = 54.63500213623, z = 10827.455078125}
	},
	{
		pA =  {x = 4747.0625, y = 41.584358215332, z = 11866.421875},
		pB = {x = 4743.23046875, y = 51.196254730225, z = 11505.842773438}
	},
	{
		pA =  {x = 6749.4487304688, y = 44.903495788574, z = 12980.83984375},
		pB = {x = 6701.4965820313, y = 52.563804626465, z = 12610.278320313}
	},
	{
		pA =  {x = 3114.1865234375, y = -42.718975067139, z = 9420.5078125},
		pB = {x = 2757, y = 53.77322769165, z = 9255}
	},
	{
		pA =  {x = 2786.8354492188, y = 53.645294189453, z = 9547.8935546875},
		pB = {x = 3002.0930175781, y = -53.198081970215, z = 9854.39453125}
	},
	{
		pA =  {x = 3803.9470214844, y = 53.730079650879, z = 7197.9018554688},
		pB = {x = 3664.1088867188, y = 54.18229675293, z = 7543.572265625}
	},
	{
		pA =  {x = 2340.0886230469, y = 60.165466308594, z = 6387.072265625},
		pB = {x = 2695.6096191406, y = 54.339839935303, z = 6374.0634765625}
	},
	{
		pA =  {x = 3249.791015625, y = 55.605854034424, z = 6446.986328125},
		pB = {x = 3157.4558105469, y = 54.080295562744, z = 6791.4458007813}
	},
	{
		pA =  {x = 3823.6242675781, y = 55.420352935791, z = 5923.9130859375},
		pB = {x = 3584.2561035156, y = 55.6123046875, z = 6215.4931640625}
	},
	{
		pA =  {x = 5796.4809570313, y = 51.673671722412, z = 5060.4116210938},
		pB = {x = 5730.3081054688, y = 54.921173095703, z = 5430.1635742188}
	},
	{
		pA =  {x = 6007.3481445313, y = 51.673641204834, z = 4985.3803710938},
		pB = {x = 6388.783203125, y = 51.673400878906, z = 4987}
	},
	{
		pA =  {x = 7040.9892578125, y = 57.192108154297, z = 3964.6728515625},
		pB = {x = 6668.0073242188, y = 51.671356201172, z = 3993.609375}
	},	
	{
		pA =  {x = 7763.541015625, y = 54.872283935547, z = 3294.3481445313},
		pB = {x = 7629.421875, y = 56.908012390137, z = 3648.0581054688}
	},
	{
		pA =  {x = 4705.830078125, y = -62.586814880371, z = 9440.6572265625},
		pB = {x = 4779.9809570313, y = -63.09009552002, z = 9809.9091796875}
	},
	{
		pA =  {x = 4056.7907714844, y = -63.152275085449, z = 10216.12109375},
		pB = {x = 3680.1550292969, y = -63.701038360596, z = 10182.296875}
	},
	{
		pA =  {x = 4470.0883789063, y = 41.59789276123, z = 12000.479492188},
		pB = {x = 4232.9799804688, y = 49.295585632324, z = 11706.015625}
	},
	{
		pA =  {x = 5415.5708007813, y = 40.682685852051, z = 12640.216796875},
		pB = {x = 5564.4409179688, y = 41.373748779297, z = 12985.860351563}
	},
	{
		pA =  {x = 6053.779296875, y = 40.587882995605, z = 12567.381835938},
		pB = {x = 6045.4555664063, y = 41.211364746094, z = 12942.313476563}
	},
	{
		pA =  {x = 4454.66015625, y = 42.799690246582, z = 8057.1313476563},
		pB = {x = 4577.8681640625, y = 53.31339263916, z = 7699.3686523438}
	},
	{
		pA =  {x = 7754.7700195313, y = 52.890430450439, z = 10449.736328125},
		pB = {x = 8096.2885742188, y = 53.66955947876, z = 10288.80078125}
	},
	{
		pA =  {x = 7625.3139648438, y = 55.008113861084, z = 9465.7001953125},
		pB = {x = 7995.986328125, y = 53.530490875244, z = 9398.1982421875}
	},
	{
		pA =  {x = 9767, y = 53.044532775879, z = 8839},
		pB = {x = 9653.1220703125, y = 53.697280883789, z = 9174.7626953125}
	},
	{
		pA =  {x = 10775.653320313, y = 55.35241317749, z = 7612.6943359375},
		pB = {x = 10665.490234375, y = 65.222145080566, z = 7956.310546875}
	},
	{
		pA =  {x = 10398.484375, y = 66.200691223145, z = 8257.8642578125},
		pB = {x = 10176.104492188, y = 64.849853515625, z = 8544.984375}
	},
	{
		pA =  {x = 11198.071289063, y = 67.641044616699, z = 8440.4638671875},
		pB = {x = 11531.436523438, y = 53.454048156738, z = 8611.0087890625}
	},
	{
		pA =  {x = 11686.700195313, y = 55.458232879639, z = 8055.9624023438},
		pB = {x = 11314.19140625, y = 58.438243865967, z = 8005.4946289063}
	},
	{
		pA =  {x = 10707.119140625, y = 55.350387573242, z = 7335.1752929688},
		pB = {x = 10693, y = 54.870254516602, z = 6943}
	},
	{
		pA =  {x = 10395.380859375, y = 54.869094848633, z = 6938.5009765625},
		pB = {x = 10454.955078125, y = 55.308219909668, z = 7316.7041015625}
	},
	{
		pA =  {x = 10358.5859375, y = 54.86909866333, z = 6677.1704101563},
		pB = {x = 10070.067382813, y = 55.294486999512, z = 6434.0815429688}
	},
	{
		pA =  {x = 11161.98828125, y = 53.730766296387, z = 5070.447265625},
		pB = {x = 10783, y = -63.57177734375, z = 4965}
	},
	{
		pA =  {x = 11167.081054688, y = -62.898971557617, z = 4613.9829101563},
		pB = {x = 11501, y = 54.571090698242, z = 4823}
	},
	{
		pA =  {x = 11743.823242188, y = 52.005855560303, z = 4387.4672851563},
		pB = {x = 11379, y = -61.565242767334, z = 4239}
	},
	{
		pA =  {x = 10388.120117188, y = -63.61775970459, z = 4267.1796875},
		pB = {x = 10033.036132813, y = -60.332069396973, z = 4147.1669921875}
	},
	{
		pA =  {x = 8964.7607421875, y = -63.284225463867, z = 4214.3833007813},
		pB = {x = 8569, y = 55.544258117676, z = 4241}
	},
	{
		pA =  {x = 5554.8657226563, y = 51.680099487305, z = 4346.75390625},
		pB = {x = 5414.0634765625, y = 51.611679077148, z = 4695.6860351563}
	},
	{
		pA =  {x = 7311.3393554688, y = 54.153884887695, z = 10553.6015625},
		pB = {x = 6938.5209960938, y = 54.441242218018, z = 10535.8515625}
	},
	{
		pA =  {x = 7669.353515625, y = -64.488967895508, z = 5960.5717773438},
		pB =  {x = 7441.2182617188, y = 54.347793579102, z = 5761.8989257813}
	},
	{
		pA =  {x = 7949.65625, y = 54.276401519775, z = 2647.0490722656},
		pB = {x = 7863.0063476563, y = 55.178623199463, z = 3013.7814941406}
	},
	{
		pA =  {x = 8698.263671875, y = 57.178703308105, z = 3783.1169433594},
		pB = {x = 9041, y = -63.242683410645, z = 3975}
	},
	{
		pA =  {x = 9063, y = 68.192077636719, z = 3401},
		pB = {x = 9275.0751953125, y = -63.257461547852, z = 3712.8935546875}
	},
	{
		pA =  {x = 12047.340820313, y = 54.830627441406, z = 6475.11328125},
		pB = {x = 12300.9375, y = 54.83561706543, z = 6818.9453125}
	},
	{
		pA =  {x = 12748.838867188, y = 58.281986236572, z = 5814.9653320313},
		pB = {x = 12400.740234375, y = 54.815074920654, z = 5860.931640625}
	},
	{
		pA =  {x = 11913.165039063, y = 54.050819396973, z = 5373.34375},
		pB = {x = 11569.1953125, y = 57.787326812744, z = 5211.7143554688}
	},	{
		pA =  {x = 9237.3603515625, y = 67.796775817871, z = 2522.8937988281},
		pB = {x = 9344.2041015625, y = 65.500213623047, z = 2884.958984375}
	},
	{
		pA =  {x = 7324.2783203125, y = 52.594970703125, z = 1461.2199707031},
		pB = {x = 7357.3852539063, y = 54.282878875732, z = 1837.4309082031}
	}


	
}

local closest = minRange+1
local startPoint = {}
local endPoint = {}
local directionVector = {}
local directionPos = {}
local lastUsedStart = {}
local lastUsedEnd = {}

local busy = false
local rivenCanJump = true
local RunOnce = false


function Plugin:__init() -- This function is pretty much the same as OnLoad, so you can do your load stuff in here
	SkillQ = AutoCarry.Skills:NewSkill(false, _Q, 260, "Broken Wings", AutoCarry.SPELL_SELF_ATMOUSE, 0, false, false) -- register muh skrillz
	SkillW = AutoCarry.Skills:NewSkill(false, _W, 250, "Ki Burst", AutoCarry.SPELL_CIRCLE, 0, false, false, 1.40, 250, 275, false)
	SkillE = AutoCarry.Skills:NewSkill(false, _E, 325, "Valor", AutoCarry.SPELL_CIRCLE, 0, false, false, 1.40, 250, 275, false)
	SkillR = AutoCarry.Skills:NewSkill(false, _R, 0, "Blade of the Exile", AutoCarry.SPELL_SELF, 0, false, false)
	SkillRC = AutoCarry.Skills:NewSkill(false, _R, 900, "Wind Slash", AutoCarry.SPELL_CONE, 0, false, false, 1.5, 1130, 30, false)

	--AutoCarry.Plugins:RegisterOnAttacked(OnAttacked)
	AdvancedCallback:bind("OnGainBuff", OnGainBuff)
	AdvancedCallback:bind("OnLoseBuff", OnLoseBuff)

	AutoCarry.Crosshair:SetSkillCrosshairRange(900)
	
	PrintChat("<font color='#00adec'> >>The Wonderful Riven LOADED!!<</font>")
	PrintChat("<font color='#00addd'> >>OVERKILL mode disabled until combo generator is complete<</font>")
end

function Plugin:OnLoad()
	PrintChat("Checking NyanKat license, don't press F9 until complete...")
	RunCmdCommand('mkdir "' .. string.gsub(SCRIPT_PATH..'/Nyan"', [[/]], [[\]]))
	baseEnc='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
 	if FileExist(tostring(SCRIPT_PATH .. "/Nyan/serial.key")) then
        local fp = io.open(tostring(SCRIPT_PATH .. "/Nyan/serial.key"), "r" )
        for line in fp:lines() do
            decode = split(descifrar(line,"regkey"),":")
            if decode then
                CheckID(decode[1],decode[2],decode[3],false)
            end
        end
  		fp:close()
    else
        local _, count = string.gsub(GetClipboardText(), ":", "")
        if count == 2 then 
        	SaveSerial(GetClipboardText())
        else
	       	PrintChat("You do not have a valid account")
	       	DoError(5)
        end
    end

	enemyHeroes = GetEnemyHeroes()
    for _, enemy in pairs(enemyHeroes) do
        for _, champ in pairs(StunList) do
            if enemy.charName == champ.charName then
                -- spacer
                local Row = {Object = enemy, charName = enemy.charName, spellName = champ.spellName}
                -- spacer
                table.insert(ToInterrupt, Row)
            end
        end
    end
end	


function Plugin:OnTick()
	local Botrk = GetInventorySlotItem(3153)
		if AutoCarry.Keys.AutoCarry and Botrk and Target and ValidTarget(Target) and Menu2.Usebork then
			CastSpell(Botrk, Target)
		end
--[[auth stuff]]
if SACstate == 3 then
	return
elseif not DoneInit then
	if not Init() then
		return
	end
end

--end auth stuff

---spacer
---spacer
---spacer
---spacer
---spacer
---spacer
---spacer
---spacer

	--if Menu4.Dev2 then TestQ() end
--[[auto leveler]]
if Menu2.autolevel then
autoLevelSetSequence(skilllevel)
end

--[[setup for riven]]
	RunOnce()
--[[setup completed]]	

--[[always run functions]]
	antimultiharass()
	antimulticombo()
	debugger()
	Drawjump()
	CalcDamage()
--[[end always on tick]]	


--[[wall jump movement correcter and setstate]]
	if Menu5.Help0 then HelpFiles() end
	if Menu2.WallJump and busy == false and QCount >= 0 then 
		--spacer--
					--spacer--
									--spacer--
													--spacer--
																	--spacer--
																					--spacer--
		myHero:MoveTo(mousePos.x, mousePos.z) end
	Target = AutoCarry.Crosshair:GetTarget()
--[[movement state end]]

--[[checks for things]]
	if AutoCarry.Keys.AutoCarry then DoCombo = true else DoCombo = false end
	if AutoCarry.Keys.MixedMode then DoHarass = true else DoHarass = false end
	if AutoCarry.Keys.LastHit then DoCombo = false DoHarass = false end
	if AutoCarry.Keys.LaneClear then DoCombo = false DoHarass = false end
	if Menu2.StunInter then CheckW() end
	if Menu2.StunBot then StunBot() end

--[[end base checks]]	

--[[disabled functions]]

	--[[if Menu2.AntiOK then 
		PrintChat("<font color='#dd3000'> >>Anti Over Kill disabled for now while combo generator is in works<</font>")
		Menu2.AntiOK = false
	 end]]
	if Menu2.OKMode then 
		PrintChat("<font color='#dd3000'> >>OVERKILL Mode disabled for now while combo generator is in works<</font>")
		Menu2.OKMode = false
	 end

--[[end disables]]	 

--[[wall jump stuff]]
			MyAccurateDelay()
		if Menu2.Walljump then WallJump2() end	
		if Menu2.WallJump and busy == false and myHero:CanUseSpell(SpellToCast()) == READY then --[[specialCondition() then]]
				closest = minRange+1
				WallJump()
				for i,group in pairs(jumpspot) do
					if (GetDistance(group.pA) < closest or GetDistance(group.pB) < closest ) then
						busy = true
						if (GetDistance(group.pA) < GetDistance(group.pB)) then
							closest = GetDistance(group.pA)
							startPoint = group.pA
							endPoint = group.pB
						else
							closest = GetDistance(group.pB)
							startPoint = group.pB
							endPoint = group.pA
						 end
					end 
				end
				if (busy == true) then
					directionVector = Vector(startPoint):__sub(endPoint)
					myHero:HoldPosition()
					Packet('S_MOVE', {x = startPoint.x, y = startPoint.z}):send()
					delay = 0.10
					MyDelayAction(changeDirection1,delay)
				else 
					myHero:MoveTo(mousePos.x, mousePos.z)
				end
			if Menu2.WallJump then
			WallJump()
			end	
		end

--[[end wall jump stuff]]







	if Menu2.Select then 
		RotateSelectedCombo()
	else
		ComboSelectorActive = true
	end

	if Menu2.SelectH then 
		RotateSelectedHarass()
	else
		HarassSelectorActive = true
	end

 if Menu2.Run then Escape() end
	if Target and Menu2.AutoKS then Killsteal() end
	--[[ do hax ]]
	if Target and Menu2.AutoKS then
		if HasUlt and GetTickCount() > REndsAt then
			SkillRC:ForceCast(Target)
		end
	end	
	--[[hax end]]	
	--[[harass 1]]
		if Target and DoHarass and Menu.Harass then
			Harass(Target)
		end
	--[[harass 1]]
		if Target and DoHarass and Menu.Harass2 then
			Harass2(Target)
		end
			if Target and DoHarass and Menu.Harass3 then
			Harass3(Target)
		end
	--[[combo 1]]
		if Target and DoCombo and Menu.Combo then
			Combo1(Target)
		end
	--[[combo 1]]
		if Target and DoCombo and Menu.Combo2 then
			Combo2(Target)
		end
			if Target and DoCombo and Menu.Combo3 then
			Combo3(Target)
		end
	--[[farm]]

if Menu2.FarmQx and AutoCarry.Keys.LastHit then
	spellfarm()
	end
if Menu2.FarmQw and AutoCarry.Keys.LaneClear then
	waveclearfarm()
	end
if Menu4.Dev2 then Weave:DoWeave(Target) end

end

--[[end farm]]


--[[on tick old garbage file]]
	--if rivenCanJump == false then PrintChat("false") else PrintChat("True") end
	--[[if Menu4.DEV then 
		PrintChat("<font color='#00adec'> >>YOUR NOT A DEV GO AWAY!!!<</font>")
		Menu4.DEV = false
	 end]]
	--[[if Menu2.WallJump then 
		PrintChat("<font color='#dd3000'> >>Wall Jump disabled for now<</font>") 
		Menu2.WallJump = false
	end]]

	--if Target then
		--if Menu4.Dev then AutoCarry.Helper:Debug("Range: "..GetDistance(Target)) end
	--end

--[[end garbage]]






--[[wall jump stuff]]
function jumpNeedsRotate() 
	if (myHero.charName == "Nidalee" or myHero.charName == "Riven") then
		return true
	else
		return false
	end
end

function oppositeCast()
	return false
end
function SpellToCast()
	if myHero.charName == "Riven" then
		return _Q
	end
	
end

function changeDirection1()
	if (jumpNeedsRotate()) then
		myHero:HoldPosition()
		Packet('S_MOVE', {x = startPoint.x+directionVector.x/rotateMultiplier, y = startPoint.z+directionVector.z/rotateMultiplier}):send()

		directionPos = Vector(startPoint)
		directionPos.x = startPoint.x+directionVector.x/rotateMultiplier
		directionPos.y = startPoint.y+directionVector.y/rotateMultiplier
		directionPos.z = startPoint.z+directionVector.z/rotateMultiplier
		
		delay = 0.06
		MyDelayAction(changeDirection2,delay)
	else 
			CastJump()
	end
end

function changeDirection2()
   
	Packet('S_MOVE', {x = startPoint.x, y = startPoint.z}):send()
	delay = 0.070
	MyDelayAction(CastJump,delay)
end

function CastJump()
	if (oppositeCast()) then
		CastSpell(SpellToCast(),startPoint.x+directionVector.x, startPoint.z+directionVector.z)
	else
		CastSpell(SpellToCast(),endPoint.x, endPoint.z)
	end
	myHero:HoldPosition()
	MyDelayAction(freeFunction,1)
end

function freeFunction()
	busy = false
end


functionDelay = 0;
functionToExecute = nil

function MyAccurateDelay()
		if (functionToExecute ~= nil and (functionDelay <= os.clock())) then
			functionDelay = nil
			functionToExecute()
			if (functionDelay == nil) then
				functionToExecute = nil
			end

		end
end

function MyDelayAction(b,a)
	functionDelay = a+os.clock()
	functionToExecute = b
end

function specialCondition()
	if (myHero.charName == "Riven") then
			return rivenCanJump;
	end		
end





--[[end wall jump stuff]]



--[[ Combo ]]

function Combo1(Target) -- ult is needed combo
		if Target and ValidTarget(Target) and GetDistance(Target) < 870 and Target.health < getDmg("R", Target, myHero) then
				if HasUlt then
					CastSpell(_R, Target.x, Target.z)
					CastSpell(_E, Target.x, Target.z)
				end
		end				
 if Target and ValidTarget(Target) and Target.health > GetTotalDamage(Target) then
		if not HasUlt and GetDistance(Target) <= ERange then
			if Menu.UseR then
			CastSpell(_R)
			CastSpell(_E, Target.x, Target.z)
			end
		end	
  end
		if Target and GetDistance(Target) <= WRange then
			if Menu.UseW then
				CastSpell(_W)
			end	
	end		

	if (not SkillR:IsReady() or HasUlt) and AutoCarry.Orbwalker:CanOrbwalkTargetFromPosition(Target, GetEDashPos(Target)) then
		if Menu.UseE then
		CastSpell(_E, Target.x, Target.z)
		end
	end

	if Menu.UseQ then
		Weave:DoWeave(Target)
	end	
end

function Combo2(Target) -- combo without ult to do if ult is not needed
	if Target and ValidTarget(Target) and GetDistance(Target) < 870 and Target.health < getDmg("R", Target, myHero) then
				if HasUlt then
					CastSpell(_R, Target.x, Target.z)
					CastSpell(_E, Target.x, Target.z)
				end
		end			
   if Target and ValidTarget(Target) and Target.health > GetTotalDamage(Target) then
		if not HasUlt and GetDistance(Target) <= ERange then
			if Menu.UseR then
			CastSpell(_R)
			CastSpell(_E, Target.x, Target.z)
			end
		end	
  end

	if (not SkillR:IsReady() or HasUlt) and AutoCarry.Orbwalker:CanOrbwalkTargetFromPosition(Target, GetEDashPos(Target)) then
		if Menu.UseE then
		CastSpell(_E, Target.x, Target.z)
		end
	end
	if Target and GetDistance(Target) <= WRange then
			if Menu.UseW then
				CastSpell(_W)
			end	
	end		
	if Menu.UseQ then
		Weave:DoWeave(Target)
	end	
end	

function Combo3(Target)
	if Target and ValidTarget(Target) and GetDistance(Target) < 870 and Target.health < getDmg("R", Target, myHero) then
				if HasUlt then
					CastSpell(_R, Target.x, Target.z)
					CastSpell(_E, Target.x, Target.z)
				end
		end			
   if Target and ValidTarget(Target) and Target.health > GetTotalDamage(Target) then
		if not HasUlt and GetDistance(Target) <= ERange then
			if Menu.UseR then
			CastSpell(_R)
			CastSpell(_E, Target.x, Target.z)
			end
		end	
  end

	if (not SkillR:IsReady() or HasUlt) and AutoCarry.Orbwalker:CanOrbwalkTargetFromPosition(Target, GetEDashPos(Target)) then
		if Menu.UseE then
		CastSpell(_E, Target.x, Target.z)
		end
	end
	if Target and GetDistance(Target) <= WRange then
			if Menu.UseW then
				CastSpell(_W)
			end	
	end		
	if Menu.UseQ then
		Weave:DoWeave(Target)
	end	
end	


--[[ Harass ]]

function Harass(Target)
	if not RetreatPos then
		RetreatPos = GetClickPos(Target)
	end
	if Target and GetDistance(Target) <= WRange then
			if Menu.UseW then
				CastSpell(_W)
			end	
	end		
	if Menu.UseQ then
		Weave:DoWeave(Target)
	end	
	if not SkillQ:IsReady() and NextQ - GetTickCount() < 3000 or QCount == 0 and GetDistance(Target) <= 150 then
		if RetreatPos then
			if ValidTarget(Target) and Menu.UseE then			
				CastSpell(_E, RetreatPos.x, RetreatPos.z)
			end	
		end
	end
end	


function Harass2(Target)
	if Target and ValidTarget(Target) and GetDistance(Target) <= 325 and GetDistance(Target) >= 210 then
		CastSpell(_E, Target.x, Target.z)
	end
	if Target and GetDistance(Target) <= WRange then
			if Menu.UseW then
				CastSpell(_W)
			end	
	end		
	if Menu.UseQ then
		Weave:DoWeave(Target)
	end	
end

function Harass3(Target)
	if ValidTarget(Target) then
			if myHero:CanUseSpell(_W) == READY and GetDistance(Target) < 250 then
				if Menu.UseW then
				CastSpell(_W)
				end
			end
			if myHero:CanUseSpell(_Q) == READY and GetDistance(Target) < 260 then
				if Menu.UseQ then
			CastSpell(_Q, Target.x, Target.z)
				end
		end
		if Menu.UseE then
		CastSpell(_E, Target.x, Target.z)
		end
	end
end

function Killsteal()
	if myHero:CanUseSpell(_R) == READY then
		for _, enemy in pairs(AutoCarry.Helper.EnemyTable) do
			if ValidTarget(enemy) and GetDistance(enemy) < 870 and GetDistance(enemy) > 500 and enemy.health < getDmg("R", enemy, myHero) then
				if HasUlt then
					CastSpell(_R, enemy.x, enemy.z)
				else
					CastSpell(_R)
				end
			end
		end
	end
end

function AntiOverkill()  -- do anti overkill calcs here
	--calc if true then local need ult == true
end	

function GetEDashPos(Target)
	MyPos = Vector(myHero.x, myHero.y, myHero.z)
	MousePos = Vector(Target.x, Target.y, Target.z)
	return MyPos - (MyPos - MousePos):normalized() * ERange
end

--[[ Farm? ]]

function spellfarm() -- hmm q farms maybes
if AutoCarry.Minions.KillableMinion and AutoCarry.Orbwalker:AttackReady() then
    	local BlacklistMinion = AutoCarry.Minions.KillableMinion
  	end
  		if not AutoCarry.Orbwalker:AttackReady() and (myHero:CanUseSpell(_Q) == READY or myHero:CanUseSpell(_E) == READY) then
    		for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
      		if Minion ~= BlacklistMinion then
        		if GetDistance(Minion) < 260 then
          				if myHero:CanUseSpell(_Q) == READY and Menu.FarmQ then
           	 				if Minion.health <= getDmg("Q", Minion, myHero) then
              					CastSpell(_Q, Minion.x, Minion.z)
              			end		
            		end
          		end
  			end
		end	
	end
end	

function waveclearfarm()
if	myHero:CanUseSpell(_Q) == READY then
	for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
		if GetDistance(Minion) < 260 then
	if myHero:CanUseSpell(_Q) == READY and Menu2.FarmQw then
	CastSpell(_Q, Minion.x, Minion.z)
	end
		end
	end	
end	
end	

--[[ Dynamic Q Range ]]
function getQRadius(overrideStacks)
	local _QCount = (overrideStacks and overrideStacks or QCount)

	if not HasUlt then
		if _QCount == 0 or _QCount == 1 or _QCount == 3 then 
			return 112.5
		elseif _QCount == 2 then
			return 150
		end
	else
		if _QCount == 0 or _QCount == 1 or _QCount == 3 then 
			return 162.5
		elseif _QCount == 2 then
			return 200
		end
	end
end

function TargetInQRange(Target, Pos)
	local Position = Pos and Pos or myHero
	return GetDistance(Target, Position) <= 260 + getQRadius()
end

function FindEPos(Target)
	MyVector = Vector(myHero.x, myHero.y, myHero.z)
	TargetVector = Vector(Pos.x, Pos.y, Pos.z)

	if GetDistance(Target) < 650 then
		CastPoint = TargetVector - (TargetVector - MyVector):normalized() * 325
	end
end		

--[[ Exploit Q thingy ]]

--[[ Menu etc ]]

function Plugin:OnAnimation(Unit, Animation)	
	if SACstate == 3 then return end
if Unit.isME and Animation:find("Attack1") or Animation:find("Attack2") or Animation:find("Attack3") or Animation:find("Crit") then
	foundattack = true
else
	foundattack = false
end	

		if Unit.isMe and Animation:find("Spell1a") then 
			QCount = 1
		elseif Unit.isMe and Animation:find("Spell1b") then 
			QCount = 2
		elseif Unit.isMe and Animation:find("Spell1c") then 
			QCount = 3
	end
	  for i, Enemy in pairs(ToInterrupt) do
    if Enemy.Object == unit and (animation:find("Idle1") or animation:find("Run")) then
      ToInterrupt[i].IsChanneling = false
    end
  end
end	

function Plugin:OnProcessSpell(Unit, Spell)
	if SACstate == 3 then return end
	if Unit.isMe and Spell.name == "RivenFeint" then
		RetreatPos = nil
	end
    for i, Enemy in pairs(ToInterrupt) do
        if Spell.name == Enemy.spellName and Unit.team ~= myHero.team then
            ToInterrupt[i].IsChanneling = true
        end
    end	
end

function DoAnimationCancel(Target)
	--[[if DoHarass and Menu.Harass or DoCombo and Menu.Combo then 	
		if QCount > 0 then
			AutoCarry.MyHero:MovementEnabled(false)
			AutoCarry.MyHero:AttacksEnabled(false)
			local movePos = GetClickPos(Target)
			if movePos then
			myHero:MoveTo(movePos.x, movePos.z)
			end
		end
	end	]]
end	

function OnAttacked()
	if DoHarass and Menu.Harass or DoCombo and Menu.Combo then 
		if Target and GetTickCount() > NextQ and SkillQ:IsReady() then 
			DoQFix = true
			CastSpell(_Q, Target.x, Target.z)
			NextQ = AutoCarry.Orbwalker:GetNextAttackTime()
		end
	end
end

function OnGainBuff(Unit, Buff)
	if SACstate == 3 then return end
	if Unit.isMe and Buff.name == "RivenFengShuiEngine" then
		IncreaseRanges()
		HasUlt = true
		REndsAt = GetTickCount() + 14000
	end
   if Unit.isMe and Buff.name == "rivenpassiveaaboost" then BonusD = true end
end

function OnLoseBuff(Unit, Buff)
	if SACstate == 3 then return end
	if Unit.isMe and Buff.name == "RivenTriCleave" then
		QCount = 0
		rivenCanJump = false
	elseif Unit.isMe and Buff.name == "RivenFengShuiEngine" then
		DecreaseRanges()
		HasUlt = false
	end
  if Unit.isMe and Buff.name == "rivenpassiveaaboost" then 
  	BonusD = false 
  	PassiveStacks = 0
  end
end

function OnUpdateBuff(Unit, Buff) 
	if SACstate == 3 then return end
	if (Unit == myHero and Buff.name == "RivenTriCleave" and Buff.stack == 2) then
		rivenCanJump = true
		rivencanq = true
	elseif Unit.isMe and Buff.name == "rivenpassiveaaboost" then
		PassiveStacks = Buff.stack
	end
end

function GetClickPos(Target)
	if Target then
		MyPos = Vector(myHero.x, myHero.y, myHero.z)
		MousePos = Vector(Target.x, Target.y, Target.z)
		return MyPos - (MyPos - MousePos):normalized() * -200
	end
end

function Plugin:OnDraw()
 if SACstate == 3 then return end
 if Menu.Combo and Menu3.Draw2 then DrawText("Combo 1 Active *SUGGESTED*",16,100, 100, 0xFF00FF00) end
 if Menu.Combo2 and Menu3.Draw2 then DrawText("Combo 2 Active",16,100, 100, 0xFF00FF00) end
 if Menu.Combo3 and Menu3.Draw2 then DrawText("Combo 3 Non-Animation Cancel Active",16,100, 100, 0xFF00FF00) end
 if Menu.Harass and Menu3.Draw1 then DrawText("Harass 1 Active *SUGGESTED*",16,100, 120, 0xFF00FF00) end
 if Menu.Harass2 and Menu3.Draw1 then DrawText("Harass 2 Active",16,100, 120, 0xFF00FF00) end
 if Menu.Harass3 and Menu3.Draw1 then DrawText("Harass 3 Non-Animation Cancel Active",16,100, 120, 0xFF00FF00) end
 --DrawText(tostring(#Weave.Combo),100,900, 400, 0xFF00FF00)
 --if foundattack == true then DrawText("yes",30,100, 400, 0xFF00FF00) else
 	 --if foundattack == false then DrawText("no",30,100, 400, 0xFF00FF00)
 	 --end
 --end	 	


 if Menu3.Draw3 then DrawText("Swap Combo Key is T",16,100, 140, 0xffffff00)	end
 if Menu3.Draw4 then DrawText("Swap Harass Key is T",16,100, 160, 0xffffff00)	end
 --if Menu4.DEV and QCount == 0 then DrawText("QCount is 0",16,100, 180, 0xffffff00)	end
 --if Menu4.DEV and QCount == 1 then DrawText("QCount is 1",16,100, 180, 0xffffff00)	end
 --if Menu4.DEV and QCount == 2 then DrawText("QCount is 2",16,100, 180, 0xffffff00)	end
 --if Menu4.DEV and QCount == 3 then DrawText("QCount is 3",16,100, 180, 0xffffff00)	end	
 --if Menu4.DEV and busy == true then DrawText("state1 is true",16,100, 200, 0xffffff00)	end	
 --if Menu4.DEV and busy == false then DrawText("state1 is false",16,100, 200, 0xffffff00)	end	
 --if Menu3.Draw6 then DrawFloats() end
--[[wall jump stuff]]

			if Menu2.WallJump then
				for i,group in pairs(jumpspot) do
					
					if (GetDistance(group.pA) < displayRange or GetDistance(group.pB) < displayRange) then
						DrawCircle(group.pA.x, group.pA.y, group.pA.z, minRange, 0x6600CC)
						DrawCircle(group.pB.x, group.pB.y, group.pB.z, minRange, 0x6600CC)
					end
			end
end
			if Menu3.Draw5 then
				for i,group in pairs(jumpspot) do
					
					if (GetDistance(group.pA) < displayRange or GetDistance(group.pB) < displayRange) then
						DrawCircle(group.pA.x, group.pA.y, group.pA.z, minRange, 0x6600CC)
						DrawCircle(group.pB.x, group.pB.y, group.pB.z, minRange, 0x6600CC)
					end
			end
end
end


function IncreaseRanges()
	WRange = 260
end

function DecreaseRanges()
	WRange = 250
end

function Escape()
	local Distance = GetDistance(mousePos)
	local MoveSqr = math.sqrt((mousePos.x - myHero.x) ^ 2 + (mousePos.z - myHero.z) ^ 2)
	local MoveX = myHero.x + Distance * ((mousePos.x - myHero.x) / MoveSqr)
	local MoveZ = myHero.z + Distance * ((mousePos.z - myHero.z) / MoveSqr)
	myHero:MoveTo(MoveX, MoveZ)
	CastSpell(_Q, MoveX, MoveZ)
	CastSpell(_E, MoveX, MoveZ)
end	

function WallJump()
	local Distance = GetDistance(mousePos)
	local MoveSqr = math.sqrt((mousePos.x - myHero.x) ^ 2 + (mousePos.z - myHero.z) ^ 2)
	local MoveX = myHero.x + Distance * ((mousePos.x - myHero.x) / MoveSqr)
	local MoveZ = myHero.z + Distance * ((mousePos.z - myHero.z) / MoveSqr)
	myHero:MoveTo(MoveX, MoveZ)
 	if Menu2.WallE then CastSpell(_E, mousePos.x, mousePos.z) end
	if QCount == 0 then
	 CastSpell(_Q, mousePos.x, mousePos.z)
	end
	if QCount == 1 then
	 CastSpell(_Q, mousePos.x, mousePos.z)
	end
end	

function WallJump2()
	local Distance = GetDistance(mousePos)
	local MoveSqr = math.sqrt((mousePos.x - myHero.x) ^ 2 + (mousePos.z - myHero.z) ^ 2)
	local MoveX = myHero.x + Distance * ((mousePos.x - myHero.x) / MoveSqr)
	local MoveZ = myHero.z + Distance * ((mousePos.z - myHero.z) / MoveSqr)
	myHero:MoveTo(MoveX, MoveZ)
	if QCount == 0 then
	 CastSpell(_Q, mousePos.x, mousePos.z)
	end
	if QCount == 1 then
	 CastSpell(_Q, mousePos.x, mousePos.z)
	end
end	

function antimulticombo()
if Menu.Combo then
Menu.Combo2 = false
Menu.Combo3 = false
end
if Menu.Combo2 then
Menu.Combo = false
Menu.Combo3 = false
end	
if Menu.Combo3 then
Menu.Combo = false
Menu.Combo2 = false
end		
end

function antimultiharass()
if Menu.Harass then
Menu.Harass2 = false
Menu.Harass3 = false
end
if Menu.Harass2 then
Menu.Harass = false
Menu.Harass3 = false
end	
if Menu.Harass3 then
Menu.Harass = false
Menu.Harass2 = false
end
end

function debugger()
--blank		
end

function RotateSelectedCombo()
	if ComboSelectorActive then
		if Menu.Combo then
			SetActiveCombo(2)
		elseif Menu.Combo2 then
			SetActiveCombo(3)
		elseif Menu.Combo3 then
			SetActiveCombo(1)
		end
		ComboSelectorActive = false
	end
end

function SetActiveCombo(selection)
	Menu.Combo = (selection == 1 and true or false)
	Menu.Combo2 = (selection == 2 and true or false)
	Menu.Combo3 = (selection == 3 and true or false)
end

function RotateSelectedHarass()
	if HarassSelectorActive then
		if Menu.Harass then
			SetActiveHarass(2)
		elseif Menu.Harass2 then
			SetActiveHarass(3)
		elseif Menu.Harass3 then
			SetActiveHarass(1)
		end
		HarassSelectorActive = false
	end
end

function SetActiveHarass(selection)
	Menu.Harass = (selection == 1 and true or false)
	Menu.Harass2 = (selection == 2 and true or false)
	Menu.Harass3 = (selection == 3 and true or false)
end


function buffdamage(Minion, HasUlt)
	local damage = 0
	if myHero.level == 1 or myHero.level == 2 then damage = 1.20 end
	if myHero.level == 3 or myHero.level == 4 or myHero.level == 5 then damage = 1.25 end
	if myHero.level == 6 or myHero.level == 7 or myHero.level == 8 then damage = 1.30 end
	if myHero.level == 9 or myHero.level == 10 or myHero.level == 11 then damage = 1.35 end
	if myHero.level == 12 or myHero.level == 13 or myHero.level == 14 then damage = 1.40 end
	if myHero.level == 15 or myHero.level == 16 or myHero.level == 17 then damage = 1.45 end
	if myHero.level == 18 then damage = 1.50 end
	if BonusD == true then
		local Damage = getDmg("AD", Minion, myHero)
		if HasUlt then
			Damage = Damage * 1.2
		end
		return Damage * damage - Damage
	end
	return 0
end

function AntiR()
	anticombo = getDmg("Q", Target, myHero) * 3 + getDmg("W", Target, myHero)
	end

function CheckW()
	if Menu2.StunInter then 	
  		for _, Enemy in pairs(ToInterrupt) do
   			 if Enemy.IsChanneling and RangeW >= GetDistance(Enemy.Object) then
      			CastSpell(_W)
      		end
      	end	
    end
end      			


function StunBot()
	if myHero:CanUseSpell(_W) == READY then
		for _, enemy in pairs(AutoCarry.Helper.EnemyTable) do
			if ValidTarget(enemy) and GetDistance(enemy) < 250 then
					CastSpell(_W)
			end
		end
	end
end		

function HelpFiles()
	if Menu5.Help1 == true  then
				--spacer
						--spacer
								--spacer
		PrintChat("<font color='#00adec'> >To use Wall Jump hold the wall jump key *default is G* and place the cursor in the purple circle of the jump point you wish to take. Hold it there until riven preforms the jump<</font>")
		Menu5.Help1 = false
	end
	if Menu5.Help2 == true  then
				--spacer
						--spacer
								--spacer
		PrintChat("<font color='#00adec'> >Stun Bot will always stun anything it can<</font>")
		Menu5.Help2 = false
	end
	if Menu5.Help3 == true  then
				--spacer
						--spacer
								--spacer
		PrintChat("<font color='#00adec'> >Always do maximum damage in combos regardless of overkill and item resources<</font>")
		Menu5.Help3 = false
	end	
	if Menu5.Help4 == true  then
				--spacer
						--spacer
								--spacer
		PrintChat("<font color='#00adec'> >Antioverkill over kill looks at if we need R in our combos to kill and saves it if we do not<</font>")
		Menu5.Help4 = false
	end
	if Menu5.Help5 == true  then
		PrintChat("<font color='#00adec'> >Chase with Q will use Q as a gap closer instead of waiting for AA range for animation cancel<</font>")
		Menu5.Help5 = false
	end
	if Menu5.Help6 == true  then
				--spacer
						--spacer
								--spacer
		PrintChat("<font color='#00adec'> >Combo 1 is an aggressive all in combo. Combo 2 is slightly less agressive gives retreat ability. Combo 3 is the standard non animation cancel using riven combo.<</font>")
		Menu5.Help6 = false
	end
	if Menu5.Help7 == true  then
		--spacer
				--spacer
						--spacer
		PrintChat("<font color='#00adec'> >Harass 1 is a quick Q animation cancel followed by W stun and E to retreat. Harass 2 is E into range W stun animation Q cancel with W. Harass 3 is the standard non animation cancel using riven harass.<</font>")
		Menu5.Help7 = false
	end
		if Menu5.Help8 == true  then
		--spacer
				--spacer
						--spacer
		PrintChat("<font color='#00adec'> >They are the best developed at the moment and my personal choice<</font>")
		Menu5.Help8 = false
	end
		if Menu5.Help9 == true  then
		--spacer
				--spacer
						--spacer
		PrintChat("<font color='#00adec'> >Q, E, W, Q, Q, R, Q, E, Q, E, R, E, E, W, W, R, W, W<</font>")
		Menu5.Help9 = false
	end
end	

function RunOnce()
	if RunOnce == false then 
		Menu.Combo = true
		Menu.Harass = true
		--Menu4.DEV = false
		Menu5.Help0 = false
	RunOnce = true
	PrintChat("The Wonderful Riven Setup Completed")
	end
end	

function TestQ()
	if Target then
	Weave:DoWeave(Target)
	end
end	

function Drawjump()
	if Menu3.Draw5 then
		displayRange = 7000
	end
    if not Menu3.Draw5 then 
     displayRange = 1000
    end
end   

--[[killtext stuff]] 
function GetBonusSpellDamage(Unit)
  if Debuffs[Unit.charName] then
    return (myHero:GetSpellData(_E).level * 3) + 5
  end
end


function CalcDamage()
 --[[ for _, Enemy in pairs(AutoCarry.Helper.EnemyTable) do
    if ValidTarget(Enemy) then
      local TotalDamage, Damage = GetDamage(Enemy)
      if Enemy.health < Damage then
        SetFloatText(Enemy, "Murder Him!!!!")
      elseif Enemy.health < TotalDamage then
        SetFloatText(Enemy, "Need cooldowns!")
      else
        SetFloatText(Enemy, "Can kill in "..math.ceil(Enemy.health - Damage).." health!")
      end
    end
  end]]
end

function GetTotalDamage(Target)
    local AADamage = getDmg("AD", Target, myHero)

    if myHero:CanUseSpell(_W) == READY then
        WDamage = getDmg("W", Target, myHero, 1)
    else
        WDamage = 0
    end

    if myHero:CanUseSpell(_Q) == READY then
        QDamage = getDmg("Q", Target, myHero)
    else
        QDamage = 0
    end

    return QDamage + AADamage + QDamage + AADamage + QDamage + AADamage + WDamage 
end  



function GetDamage(target)
  local damage = 0
  local totalDamage = 0
  
  totalDamage, damage = GetDamages(3146, "HXG", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3144, "BWC", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3100, "LICHBANE", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3151, "LIANDRYS", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3188, "BLACKFIRE", nil, target, damage, totalDamage)

  if GetInventorySlotItem(3128) then
    if myHero:CanUseSpell(GetInventorySlotItem(3128)) == READY then
      damage = damage + (GetInventorySlotItem(3128) and getDmg("DFG",enemy,myHero) or 0)
      damage = damage * 1.2
    end
    totalDamage = totalDamage + (GetInventorySlotItem(3128) and getDmg("DFG",enemy,myHero) or 0)
    totalDamage = totalDamage * 1.2
  end
  
  totalDamage, damage = GetDamages(nil, "AD", nil, target, damage, totalDamage)
  
  totalDamage, damage = GetDamages(3153, "RUINEDKING", 2, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3057, "SHEEN", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3078, "TRINITY", nil, target, damage, totalDamage)
  totalDamage, damage = GetDamages(3025, "ICEBORN", nil, target, damage, totalDamage)

  
  if myHero:CanUseSpell(_Q) == READY then totalDamage, damage = GetDamages(nil, "Q", nil, target, damage, totalDamage) * 3 end
  if myHero:CanUseSpell(_W) == READY then totalDamage, damage = GetDamages(nil, "W", nil, target, damage, totalDamage) end
  if myHero:CanUseSpell(_R) == READY then totalDamage, damage = GetDamages(nil, "R", nil, target, damage, totalDamage) end


  -- if target has E debuff
  
  return totalDamage, damage
end

function GetDamages(item, text, level, target, dmg, total)
local dmg = 0	
  local damage = ((item and GetInventorySlotItem(item)) and getDmg (text, target, myHero, level) or 0)
  if item then 
    currentDamage = ((GetInventorySlotItem(item) and myHero:CanUseSpell(GetInventorySlotItem(item)) == READY) and getDmg (text, target, myHero, level) or 0)
  else 
    currentDamage = getDmg(text, target, myHero)
  end
  if text == "R" then
    damage = damage * 3
    currentDamage = currentDamage * 3
  end
  return total + damage, dmg + currentDamage
end  


local Floats = {}

function SetFloatText(Unit, Text)
  local U = GetUnitFloatIndex(Unit)
  if U then
    Floats[U].Text = Text
  else
    table.insert(Floats, {Unit = Unit, Text = Text})
  end
end


function DrawFloats()
  if #Floats > 0 then
    PrintFloatText(Floats[1].Unit, 0, Floats[1].Text)
      table.remove(Floats, 1)
  end
end

function GetUnitFloatIndex(Unit)
  for i, Float in pairs(Floats) do
    if Float.Unit == Unit then 
      return i
    end
  end
end

--expirimental 

--[[function PositionUnderTower(Pos)
    local ClosestTower = AutoCarry.Structures:GetClosestEnemyTower(Pos)

    if ClosestTower and GetDistance(Pos, ClosestTower) <= AutoCarry.Structures.TowerRange + 200 then
        return true
    end
end

function GetEnemiesAroundPos(Pos)
    local Count = 0
    for _, Enemy in pairs(AutoCarry.Helper.EnemyTable) do
        if GetDistance(Enemy, Pos) <= 800 then --TODO: Find a good range
            Count = Count + 1
        end
    end
    return Count
end

function GetAlliesAroundPos(Pos)
    local Count = 0
    for _, Ally in pairs(AutoCarry.Helper.AllyTable) do
        if Ally ~= myHero and GetDistance(Ally, Pos) <= 800 then --TODO: Find a good range
            Count = Count + 1
        end
    end]]
    --return Count

--end expirimental    

--[[Menu6 = scriptConfig("The Wonderful Riven Guide files ", "Nyanguide")
Menu6:addParam("sep", "-- Turn off Guide files to decrease lag slightly --", SCRIPT_PARAM_INFO, "")
Menu6:addParam("guide1", "Enable Guide Files", SCRIPT_PARAM_ONOFF, false)
Menu6:addParam("sep", "-- Enable help files first then  --", SCRIPT_PARAM_INFO, "")
Menu6:addParam("sep", "-- Starting Items  --", SCRIPT_PARAM_INFO, "")
Menu6:addParam("guide2", "Enable Guide Files", SCRIPT_PARAM_ONOFF, false)]]



function SetupMenus()
	
	AutoCarry.Plugins:RegisterBonusLastHitDamage(buffdamage)
	Menu:addParam("sep", "-- AutoCarry Combos--", SCRIPT_PARAM_INFO, "")
	Menu:addParam("Combo", "Combo one In Autocarry", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("Combo2", "Combo 2 in Autocarry", SCRIPT_PARAM_ONOFF, false)
	Menu:addParam("Combo3", "Non-Animation Cancel Combo 3", SCRIPT_PARAM_ONOFF, false)
	Menu:addParam("sep", "-- only one of the above allowed at a time!--", SCRIPT_PARAM_INFO, "")
	Menu:addParam("sep", "", SCRIPT_PARAM_INFO, "")

	Menu:addParam("sep", "-- Mixmode Harasses--", SCRIPT_PARAM_INFO, "")
	Menu:addParam("Harass", "Harass one in MixedMode", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("Harass2", "Harass 2 in MixedMode", SCRIPT_PARAM_ONOFF, false)
	Menu:addParam("Harass3", "Non-Animation Cancel Harass 3", SCRIPT_PARAM_ONOFF, false)
	Menu:addParam("sep", "-- only one of the above allowed at a time!--", SCRIPT_PARAM_INFO, "")
	Menu:addParam("OVERQ", "Use Secondary Q Cancel type", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("UseQ", "Allow script to use Q", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("UseW", "Allow script to use W", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("UseE", "Allow script to use E", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("UseR", "Allow script to use R", SCRIPT_PARAM_ONOFF, true)

	Menu:addParam("sep", "", SCRIPT_PARAM_INFO, "")

	Menu2 = scriptConfig("The Wonderful Riven Misc Functions", "Nyanmisc")

	Menu2:addParam("sep", "-- Farming --", SCRIPT_PARAM_INFO, "")
	Menu2:addParam("FarmQx", "Farm With Q in LastHit", SCRIPT_PARAM_ONOFF, true)
	Menu2:addParam("FarmQw", "Farm With Q in WaveClear", SCRIPT_PARAM_ONOFF, true)
	Menu2:addParam("sep", "", SCRIPT_PARAM_INFO, "")

	Menu2:addParam("sep", "-- Escape --", SCRIPT_PARAM_INFO, "")
	Menu2:addParam("Run", "Escape Combo", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
	Menu2:addParam("WallJump", "Wall Jump Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("G"))
	Menu2:addParam("WallE", "Use E In WallJump Mode", SCRIPT_PARAM_ONOFF, true)
	Menu2:addParam("sep", "", SCRIPT_PARAM_INFO, "")

	Menu2:addParam("sep", "-- Misc Functions --", SCRIPT_PARAM_INFO, "")
	Menu2:addParam("Usebork", "Use BoRK In Autocarry", SCRIPT_PARAM_ONOFF, true)
	Menu2:addParam("StunBot", "Stun Bot", SCRIPT_PARAM_ONOFF, true)
	Menu2:addParam("autolevel", "Auto Level Skills", SCRIPT_PARAM_ONOFF, true)
	Menu2:addParam("StunInter", "Interupt high importance spells with W", SCRIPT_PARAM_ONOFF, true)
	Menu2:addParam("AutoKS", "Auto KS with R", SCRIPT_PARAM_ONOFF, true)
	Menu2:addParam("OKMode", "OVERKILL Mode", SCRIPT_PARAM_ONOFF, false)
	Menu2:addParam("AntiOK", "Use AntiOverkill", SCRIPT_PARAM_ONOFF, false)
	Menu2:addParam("Chase", "Chase With Q", SCRIPT_PARAM_ONOFF, true)
	Menu2:addParam("Select", "Swap Combos", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
	Menu2:addParam("SelectH", "Swap Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
	Menu2:addParam("sep", "", SCRIPT_PARAM_INFO, "")

	Menu3 = scriptConfig("The Wonderful Riven Draw Functions", "Nyandraw")

	Menu3:addParam("sep", "-- Draw Functions --", SCRIPT_PARAM_INFO, "")
	Menu3:addParam("Draw1", "Draw Current Harass", SCRIPT_PARAM_ONOFF, true)
	Menu3:addParam("Draw2", "Draw Current Combo", SCRIPT_PARAM_ONOFF, true)
	Menu3:addParam("Draw3", "Draw Quick Combo Switch Key", SCRIPT_PARAM_ONOFF, true)
	Menu3:addParam("Draw4", "Draw Quick Harass Switch Key", SCRIPT_PARAM_ONOFF, true)
	Menu3:addParam("Draw5", "Always Draw Jump Points", SCRIPT_PARAM_ONOFF, false)
	--Menu3:addParam("Draw6", "Draw Kill Text", SCRIPT_PARAM_ONOFF, false)

	--Menu4 = scriptConfig("The Wonderful Riven Dev Functions", "Nyandev")

	--Menu4:addParam("sep", "-- DEV Functions --", SCRIPT_PARAM_INFO, "")
	--Menu4:addParam("DEV", "Draw DEV Functions", SCRIPT_PARAM_ONOFF, false)
	--Menu4:addParam("Dev2", "Dev key press test", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("J"))


	Menu5 = scriptConfig("The Wonderful Riven Help Files", "Nyanhelp")

	Menu5:addParam("sep", "-- Turn off help files to decrease lag slightly --", SCRIPT_PARAM_INFO, "")
	Menu5:addParam("Help0", "Enable Help Files", SCRIPT_PARAM_ONOFF, false)
	Menu5:addParam("sep", "-- Enable help files first then  --", SCRIPT_PARAM_INFO, "")
	Menu5:addParam("sep", "-- Click on the off button to print help file in chat --", SCRIPT_PARAM_INFO, "")
	Menu5:addParam("Help1", "How To Use WallJump", SCRIPT_PARAM_ONOFF, false)
	Menu5:addParam("Help2", "What is stunbot?", SCRIPT_PARAM_ONOFF, false)
	Menu5:addParam("Help3", "What does OVERKILL mode do?", SCRIPT_PARAM_ONOFF, false)
	Menu5:addParam("Help4", "What does antioverkill mode do?", SCRIPT_PARAM_ONOFF, false)
	Menu5:addParam("Help5", "What does Chase with Q do?", SCRIPT_PARAM_ONOFF, false)
	Menu5:addParam("Help6", "What is combo 1/2/3?", SCRIPT_PARAM_ONOFF, false)
	Menu5:addParam("Help7", "What is harass 1/2/3?", SCRIPT_PARAM_ONOFF, false)
	Menu5:addParam("Help8", "Why is Combo1/Harass 1 suggested?", SCRIPT_PARAM_ONOFF, false)
	Menu5:addParam("Help9", "What is spell Autolevel order?", SCRIPT_PARAM_ONOFF, false)
end

_AA = 9
class 'Weave'

function Weave:__init()
	self.Combo = {}
	self.CanQ = true
	self.NextQ = 0
	self.LastQStacks = 0

	AdvancedCallback:bind("OnUpdateBuff", function(Unit, Buff) self:_OnUpdateBuff(Unit, Buff) end)
	AdvancedCallback:bind("OnGainBuff", function(Unit, Buff) self:_OnGainBuff(Unit, Buff) end)
	AdvancedCallback:bind("OnLoseBuff", function(Unit, Buff) self:_OnLoseBuff(Unit, Buff) end)
	AddAnimationCallback(function(Unit, Animation) self:_OnAnimation(Unit, Animation) end)
end

function Weave:DoWeave(Target)
	AutoCarry.Helper:Debug(tostring(#self.Combo))
	if #self.Combo > 0 then
		--spacer
		--spacer
		if self.Combo[1] == _Q  then
			--spacer
			--spacer
			if (Menu2.Chase and not AutoCarry.Orbwalker:CanOrbwalkTarget(Target) and TargetInQRange(Target)) or AutoCarry.Orbwalker:CanOrbwalkTarget(Target) then
				CastSpell(_Q, Target.x, Target.z)
				--spacer
				--spacer
				self.NextQ = AutoCarry.Orbwalker:GetNextAttackTime()
			end
		elseif self.Combo[1] == _AA and self.CanAttack then
			--spacer
			--spacer
			if not Menu2.Chase or AutoCarry.Orbwalker:CanOrbwalkTarget(Target) then
				--spacer
				--spacer
				myHero:Attack(Target)
				--spacer
				--spacer
			elseif (Menu2.Chase and not AutoCarry.Orbwalker:CanOrbwalkTarget(Target) and TargetInQRange(Target)) then
				--spacer
				--spacer
				table.remove(self.Combo, 1)
				--spacer
				--spacer
			end
		end
	else
		self.Combo = {_Q, _AA, _Q, _AA, _Q, _AA} --TODO set based on stacks
	end
end

function Weave:OnAttacked()
	-- self.CanQ = true

	-- if self.Combo and self.Combo[1] == _AA and AutoCarry.Orbwalker:IsAfterAttack() then
	-- 	table.remove(self.Combo, 1)
	-- end
end

function Weave:GetClickPos(Target)
	if Target then
		MyPos = Vector(myHero.x, myHero.y, myHero.z)
		MousePos = Vector(Target.x, Target.y, Target.z)
		return MyPos - (MyPos - MousePos):normalized() * -80
	end
end

function Weave:_OnAnimation(Unit, Animation)
	if Unit.isMe then
--spacer
--spacer
		if Animation:find("Spell1a") or Animation:find("Spell1b") or Animation:find("Spell1c") then
			--PrintChat(Animation)
			--spacer
			--spacer
			if Target then
				--spacer
				--spacer
				if #self.Combo > 0 and self.Combo[1] == _Q then
					--spacer
					--spacer
					Stage = 2
					table.remove(self.Combo, 1)
					--spacer
					--spacer
				end
				self.CanQ = false
				self.CanAttack = false
				if Menu.OVERQ then
					SendChat("/dance")
				else
					local pos = self:GetClickPos(Target)
					Packet('S_MOVE', {x = pos.x, y = pos.z}):send()
				end
			end
		end
	elseif Unit.isMe and Animation:find("Run") or Animation:find("Idle1") or Animation:find("Dance") then
		if Target and AutoCarry.Orbwalker:CanOrbwalkTarget(Target) and ValidTarget(Target) then 
			--spacer
			--spacer
			AutoCarry.Orbwalker:ResetAttackTimer()
			--spacer
			--spacer
			self.CanAttack = true
		end	
	end
end	

function Weave:_OnUpdateBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "rivenpassiveaaboost" then
		self:HandleBuff(Buff)
	end
end

function Weave:_OnGainBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "rivenpassiveaaboost" then
		self:HandleBuff(Buff)
	end
end

function Weave:_OnLoseBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "rivenpassiveaaboost" then
		if self.Combo and self.Combo[1] == _AA then
			table.remove(self.Combo, 1)
		end
		self.LastQStacks = 0
	end
end

function Weave:HandleBuff(Buff)
	if Buff.stack < self.LastQStacks then
		Stage = 1
		if self.Combo and self.Combo[1] == _AA then
			table.remove(self.Combo, 1)
		end
	end
	self.LastQStacks = Buff.stack
end

function Weave:Next()
	if self.Combo then return self.Combo[1] else return "" end
end


class 'Combo'

function Combo:__init()
	self.Enemies = {}
	self.KillStealCombo = {}

	AddDrawCallback(function() self:_OnDraw() end)
	AddTickCallback(function() self:_OnTick() end)
	AddAnimationCallback(function(Unit, Animation) self:_OnAnimation(Unit, Animation) end)
end

function Combo:_OnTick()
	--self:PerformKillsteal()
	local KillSteal = self:GetKillstealTarget()
	if KillSteal then
		AutoCarry.Helper:Debug("We can kill "..KillSteal.Target.charName)
		self.KillStealCombo = KillSteal
	end
end

function Combo:_OnAnimation(Unit, Animation)
	if Unit.isMe and Animation:find("Spell1c") then 
		if self.KillStealCombo then
			if self.KillStealCombo.Combo[1] == _Q then
				table.remove(self.KillStealCombo.Combo, 1)
			end
		end
	end
end


function Combo:PerformKillsteal()
	if self.KillStealCombo and self.KillStealCombo.Combo then
		local Target = self.KillStealCombo.Target
		local Combo = self.KillStealCombo.Combo

		if Combo[1] == _Q then
			self:DoQ(Target)
		elseif Combo[1] == _W then
			self:DoW(Target)
		end
	end
end

function Combo:DoQ(Target)
	Weave:DoWeave(Target)
	if QCount == 3 or QCount == 0 then --TODO: Changes
		table.remove(self.KillStealCombo.Combo, 1)
	end
end

function Combo:DoW(Target)
	if ValidTarget(Target, 250) then
		CastSpell(_W)
	end
	if myHero:CanUseSpell(_W) ~= READY then
		table.remove(self.KillStealCombo.Combo, 1)
	end
end

function Combo:DoE(Target)
	CastSpell(_E, Target)

	if myHero:CanUseSpell(_E) ~= READY then
		table.remove(self.KillStealCombo.Combo, 1)
	end
end

function Combo:GetKillstealTarget()
	for _, Enemy in pairs(AutoCarry.Helper.EnemyTable) do
		if ValidTarget(Enemy, 900) then
			local Combo = ComboGenerator:GetCombo(Enemy)
			if Combo then
				return Combo
			end
		end
	end
	self.KillStealCombo = nil
end

function Combo:GetDamage()
	for _, Enemy in pairs(AutoCarry.Helper.EnemyTable) do
		local Damage = ComboGenerator:GetRDamage(Target) + ComboGenerator:QWER(Target)
		if Enemy.health > Damage then
			self.Enemies[Enemy.charName] = Enemy.health - Damage
		end
	end
end

function Combo:_OnDraw()
	for _, Enemy in pairs(AutoCarry.Helper.EnemyTable) do
		if self.Enemies[Enemy.charName] then
			PrintFloatText(Enemy, 0, self.Enemies[Enemy.charName].." health remaining")
		end
	end
end




class 'ComboGenerator'

function ComboGenerator:__init()
	self.Enemies = AutoCarry.Helper.EnemyTable
	self.Combo = {}
end

-- function ComboGenerator:PerformCombo(Target)
-- 	if #Combo > 0 then -- We have some spells to cast
-- 		local Spell = Combo[1]

-- 		if Spell == _Q then
-- 			AutoCarry.Helper:Debug("Q")
-- 		elseif Spell == _W then
-- 			AutoCarry.Helper:Debug("W")
-- 		elseif Spell == _E then
-- 			AutoCarry.Helper:Debug("E")
-- 		elseif Spell == _R then
-- 			AutoCarry.Helper:Debug("R")
-- 		end

-- 		if Spell == _Q and QCount == 3 then
-- 			table.remove(Combo, 1)
-- 		elseif Spell ~= _Q then
-- 			table.remove(Combo, 1)
-- 		end
-- 	end
-- end

function ComboGenerator:GetCombo(Target)
	local ComboDamage = self:Q(Target)

	if Target.health < ComboDamage then
		local _Combo = {_Q}
		return {Combo = _Combo, Target = Target, Damage = ComboDamage}
	end

	ComboDamage = self:W(Target)
	if Target.health < ComboDamage then
		local _Combo = {_W}
		local _Enemy = Target
		return {Combo = _Combo, Target = Target, Damage = ComboDamage}
	end

	ComboDamage = self:QW(Target)
	if Target.health < ComboDamage then
		local _Combo = {_Q, _W}
		local _Enemy = Target
		return {Combo = _Combo, Target = Target, Damage = ComboDamage}
	end




	-- if Target.health < self:Q(Target) then
	-- 	Combo = {_Q}

	-- elseif Target.health < self:W(Target) then
	-- 	Combo = {_W}
	-- elseif Target.health < self:QW(Target) then
	-- 	Combo = {_Q, _W}
	-- elseif Target.health < self:QE(Target) then
	-- 	Combo = {_E, _Q}
	-- elseif Target.health < self:WE(Target) then
	-- 	Combo = {_E, _W}
	-- elseif Target.health < self:QWE(Target) then
	-- 	Combo = {_E, _Q, _W}
	-- elseif Target.health < self:GetRDamage(Target) + self:QR(Target) then
	-- 	Combo = {_R, _Q}
	-- elseif Target.health < self:GetRDamage(Target) + self:W(Target) then
	-- 	Combo = {_R, _W}
	-- elseif Target.health < self:GetRDamage(Target) + self:W(Target) + self:QR(Target) then
	-- 	Combo = {_R, _Q, _W}
	-- elseif Target.health < self:GetRDamage(Target) + self:QER(Target) then
	-- 	Combo = {_R, _E, _Q}
	-- elseif Target.health < self:GetRDamage(Target) + self:WE(Target) then
	-- 	Combo = {_R, _E, _W}
	-- elseif Target.health < self:GetRDamage(Target) + self:QWER(Target) then
	-- 	Combo = {_R, _E, _Q, W}
	-- end


end

function ComboGenerator:Q(Target)
	return self:GetQDamage(Target)
end

function ComboGenerator:W(Target)
	local WDamage = 0

	if myHero:CanUseSpell(_W) == READY and GetDistance(Target) < 250 then
		WDamage = getDmg("W", Target, myHero)
	end

	return WDamage
end

function ComboGenerator:QW(Target)
	local QDamage, WDamage = 0, 0

	QDamage = self:GetQDamage(Target)
	WDamage = (QDamage > 0 and self:GetWDamage(Target) or 0)

	return QDamage + WDamage
end

function ComboGenerator:QE(Target)
	local QDamage = 0
	local EPos = nil

	EPos = FindEPos(Target)
	QDamage = self:GetQDamage(Target, EPos)

	return QDamage
end

function ComboGenerator:WE(Target)
	local EPos = nil
	local WDamage = 0

	EPos = FindEPos(Target)
	WDamage = (QDamage > 0 and self:GetWDamage(Target, EPos) or 0)

	return WDamage
end

function ComboGenerator:QWE(Target)
	local QDamage = 0
	local WDamage = 0
	local EPos = nil

	EPos = FindEPos(Target)
	QDamage = self:GetQDamage(Target, EPos)
	WDamage = (QDamage > 0 and self:GetWDamage(Target, EPos) or 0)

	return QDamage + WDamage
end

function ComboGenerator:QR(Target)
	return self:GetQDamage(Target, nil, true)
end

function ComboGenerator:QER(Target)
	local QDamage = 0
	local EPos = nil

	EPos = FindEPos(Target)
	QDamage = self:GetQDamage(Target, EPos, true)

	return QDamage
end

function ComboGenerator:QWER(Target)
	local QDamage = 0
	local WDamage = 0
	local EPos = nil

	EPos = FindEPos(Target)
	QDamage = self:GetQDamage(Target, EPos, true)
	WDamage = (QDamage > 0 and self:GetWDamage(Target, EPos) or 0)

	return QDamage + WDamage
end

function ComboGenerator:GetWDamage(Target, Pos)
	local Position = Pos and Pos or myHero
	local WDamage = 0

	if myHero:CanUseSpell(_W) == READY and GetDistance(Target, Position) < 250 then
		WDamage = getDmg("W", Target, myHero)
	end
	return WDamage
end

function ComboGenerator:GetQDamage(Target, Pos, HasUlt)
	local Position = Pos and Pos or myHero
	local QDamage = 0

	if myHero:CanUseSpell(_Q) == READY or QCount > 0 then
		if QCount == 0 then
			if TargetInQRange(Target, Position) then																	-- Target is in range for 1st Q
				QDamage = getDmg("Q", Target, myHero) * 3
				QDamage = QDamage + (self:GetAADamage(Target, true, HasUlt) * 2)
			elseif GetDistance(Target, Position) <= getQRadius() + getQRadius(1) then 									-- Target is in range for 2nd Q
				QDamage = getDmg("Q", Target, myHero) * 2
				QDamage = QDamage + self:GetAADamage(Target, true, HasUlt)
			elseif GetDistance(Target, Position) <= getQRadius() + getQRadius(1) + getQRadius(2) then					-- Target is in range for 3rd Q
				QDamage = getDmg("Q", Target, myHero)
			end
		elseif QCount == 1 then
			if TargetInQRange(Target, Position) then
				QDamage = getDmg("Q", Target, myHero) * 2
				QDamage = QDamage + self:GetAADamage(Target, true, HasUlt)
			elseif GetDistance(Target, Position) <= getQRadius() + getQRadius(2) then
				QDamage = getDmg("Q", Target, myHero)
			end 
		elseif QCount == 2 then
			if TargetInQRange(Target, Position) then
				QDamage = getDmg("Q", Target, myHero)
			end
		end
	end
	return QDamage
end

function ComboGenerator:GetAADamage(Target, WillGainStack, HasUlt)
	local PassiveDamage = 0
	local MyDamage = 0
	if PassiveStacks > 0 or WillGainStack then
		if HasUlt then
			PassiveDamage = buffdamage(Target, true)
			MyDamage = getDmg("AD", Target, myHero) * 1.2
		else
			PassiveDamage = buffdamage(Target)
			MyDamage = getDmg("AD", Target, myHero)
		end
	end

	local TotalDamage = MyDamage + PassiveDamage

	return getDmg("AD", Target, myHero)
end

function ComboGenerator:GetRDamage(Target)
	return getDmg("R", Target, myHero)
end


function Init()
	ComboGenerator = ComboGenerator()
	Combo = Combo()
	Weave = Weave()
	SetupMenus()
	--AutoCarry.Plugins:RegisterOnAttacked(function() Weave:OnAttacked() end)
	Menu.Combo = true
	Menu.Combo2 = false
	Menu.Combo3 = false
	Menu.Harass = true
	Menu.Harass2 = false
	Menu.Harass3 = false
	DoneInit = true
end



function SaveSerial(key)
    Values = split(key,":")
    local file = io.open(tostring(SCRIPT_PATH .. "/Nyan/serial.key"), "w" )
    file:write(cifrar(key,"regkey"))
    file:close()
    CheckID(Values[1],Values[2],Values[3],true)
end

function split(pString, pPattern)
    local Table = {}
    local fpat = "(.-)" .. pPattern
    local last_end = 1
    local s, e, cap = pString:find(fpat, 1)
    while s do
        if s ~= 1 or cap ~= "" then
            table.insert(Table,cap)
        end
        last_end = e+1
        s, e, cap = pString:find(fpat, last_end)
    end
    if last_end <= #pString then
        cap = pString:sub(last_end)
        table.insert(Table, cap)
    end
    return Table
end

function url_encode(str)
  if (str) then
    str = string.gsub (str, "\n", "\r\n")
    str = string.gsub (str, "([^%w %-%_%.%~])",
        function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = string.gsub (str, " ", "+")
  end
  return str	
end
	
function enc(data)
    return ((data:gsub('.', function(x) 
        local r,baseEnc='',x:byte()
        for i=8,1,-1 do r=r..(baseEnc%2^i-baseEnc%2^(i-1)>0 and '1' or '0') end
        return r;
    end)..'0000'):gsub('%d%d%d?%d?%d?%d?', function(x)
        if (#x < 6) then return '' end
        local c=0
        for i=1,6 do c=c+(x:sub(i,i)=='1' and 2^(6-i) or 0) end
        return baseEnc:sub(c+1,c+1)
    end)..({ '', '==', '=' })[#data%3+1])
end

function cifrar(str, pass)
	crypt = {}
	local seed = 0
	for i = 1, pass:len() do
		seed = seed + pass:byte(i) + i
	end
	math.randomseed(seed)
	for i = 1, str:len() do
		table.insert(crypt, string.char(str:byte(i) + math.random()*15))
	end
	return table.concat(crypt)
end

function FinishCheck(result)
if string.find(result,"VIP") and VIP_USER then
		SACstate = 2
	elseif string.find(result,"FREE") then
		SACstate = 2
	elseif  string.find(result,"Username") then
		PrintChat("Username is already taken.")
		SACstate = 3
		if FileExist(tostring(SCRIPT_PATH .. "/Nyan/serial.key")) then
			os.remove(SCRIPT_PATH .. "/Nyan/serial.key")
		end
		DoError(1)
	elseif string.find(result,"Serial is Invalid") then
		PrintChat("Invalid serial")
		SACstate = 3
		DoError(2)
	elseif string.find(result,"HWID Invalid") then
		PrintChat("HWID Mismatch: please contact Nyankat")
		SACstate = 3
		DoError(3)
	elseif string.find(result, "suspended") then
		SACstate = 3
		PrintChat("Your license has been suspended.")
		DoError(4)
	else
		SACstate = 3
		PrintChat("You do not have a valid license.")
		DoError(5)
	end
end

function FinishRegister(result)
	if result and string.find(result,"claimed") then
		PrintChat("Key already claimed")
		SACState = 3
		if FileExist(tostring(SCRIPT_PATH .. "/Nyan/serial.key")) then
		end
	elseif string.find(result,"SUCCESS") then
		PrintChat("Key successfully registered. Please reload script.")
		SACstate = 2
	end
end	

function CheckID(User,Pass,Serial,Register)
    local authPath = "Auth/"
    local Host = "nyankat.sidascripts.com"	
	local text = tostring(os.getenv("PROCESSOR_IDENTIFIER") .. os.getenv("USERNAME") .. os.getenv("COMPUTERNAME") .. os.getenv("PROCESSOR_LEVEL") .. os.getenv("PROCESSOR_REVISION"))
	local hwid = url_encode(enc(text))
	--local hwid = url_encode(enc(text))
	local result = ""

   -- PrintChat(Host..authPath.."auth.php".."?a=login&user="..User.."&pass="..Pass.."&hwid="..hwid)
   	math.randomseed(tonumber(tostring(os.time()):reverse():sub(1,6))*tonumber(myHero.health)+tonumber(myHero.networkID))
	local randomNumber = math.random(1,999999)
	if Register then
		GetAsyncWebResult(Host,authPath.."auth.php".."?a=activateSerial&serial="..Serial.."&user="..User.."&pass="..Pass.."&hwid="..hwid,FinishRegister)
	else
		 GetAsyncWebResult(Host,authPath.."auth.php".."?a=login&user="..User.."&pass="..Pass.."&hwid="..hwid.."&bol="..GetUser().."&junk="..randomNumber,FinishCheck)
	end
end

function descifrar(str, pass)
    uncry = {}
    local seed = 0
    for i = 1, pass:len() do
        seed = seed + pass:byte(i) + i
    end
    math.randomseed(seed)
    for i = 1, str:len() do
        table.insert(uncry, string.char(str:byte(i) - math.random()*15))
    end
    return table.concat(uncry)
end

function DoError(id)
	RunCmdCommand("start /max http://sidascripts.com/error.php?errorid="..id)
	RunCmdCommand("%WINDIR%\\System32\\taskkill.exe /IM \"BoL Studio.exe\" /F")
	RunCmdCommand("%WINDIR%\\System32\\taskkill.exe /IM \"League of legends.exe\" /F")
end

Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "The Wonderful Riven")